<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Illuminate\Support\Collection;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\V;
use chillerlan\QRCode\{QRCode, QROptions};
use Kirby\Exception\NotFoundException;
use stdClass;

require_once __DIR__.'/../vendor/autoload.php';

class Item
{
    public static string $tableName = "items";

    /**
     * Creates a new item with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        return self::update(uuid(), $input);
    }

    /**
     * Deletes an item by item id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();
    }

    /**
     * Finds an item by id and throws an exception
     * if the item cannot be found
     *
     * @param string $id
     * @return string return the item found or null
     */
    public static function find(string $id): Collection
    {
        return DB::table(self::$tableName)->where('id', '=', $id)->get();
    }

    /**
     * Lists all items from the items.json
     *
     * @return array
     */
    public static function list(): array
    {
        if(!Database::hasTable(self::$tableName)) {
            Database::init();
        };

        return DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function count(): int
    {
        $items = self::list();

        return count($items);
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function getNumberOfItemsLended(): int
    {
        $items = self::list();
        $numberOfItemsLended = 0;

        foreach($items as $item) {
            $numberOfItemsLended += ($item->quantity - $item->current_quantity);
        }

        return $numberOfItemsLended;
    }

    /**
     * Updates an item by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     * @throws NotFoundException
     */
    public static function update(string $id, array $input): bool
    {
        $QrCode = new QRCode;

        $input['kirby_uuid'] = $id;
        $input['qr_code'] = $QrCode->render($id);

        // The end date must be greater than the start date
        if (V::required($input['title']) === false) {
            throw new InvalidArgumentException('A title must be set');
        }

        // if there is already an item with this same uuid we update it
        // otherwise we create it
        return DB::table(self::$tableName)->updateOrInsert(
            ['kirby_uuid' => $id],
            $input);
    }

    /**
     * Return a collection of items from in items.json
     *
     * @return array
     * @throws NotFoundException
     */
    public static function collection(): array
    {

        $items = self::list();
        $collection = [];

        foreach ($items as $item) {

            $category = '';
            if($item->category_id) {
                $category = Category::find($item->category_id);
                $item->category = $category->title;
            }

            $collection[] = [
                'text' => $item->title,
                'link' => 'lendmanagement/inventory/item/' . $item->id,
                'info' => $category ?? '',
                'image' => [
                    'icon' => 'tag',
                    'back' => 'purple-400'
                ]
            ];
        }
        return $collection;
    }

    public static function getTotalItemsByCategoryId(string $categoryId): int
    {
        $items = self::list();
        $ttl = 0;

        foreach ($items as $item) {
            if($item->category_id === $categoryId) {
                $ttl++;
            }
        }

        return $ttl;
    }

    public static function getItemsByCategory(string $categoryId): array
    {
        $items = self::list();
        $collection = [];

        foreach ($items as $item) {
            if($item->category_id === $categoryId) {
                $collection[] = [
                    'text' => $item->title,
                    'link' => 'lendmanagement/inventory/item/' . $item->id,
                    'info' => $item->quantity. " pcs",
                    'image' => [
                        'icon' => 'tag',
                        'back' => 'purple-400'
                    ]
                ];
            }
        }

        return $collection;
    }

    /**
     *
     * @return array
     */
    public static function getOptions(): array
    {
        $items = self::list();
        $options = [];
        foreach ($items as $item) {
            $options[] = [
                'text' => $item->title,
                'value' => $item->id,
            ];
        }
        return $options;
    }

    public static function getItemsByIds(mixed $itemIds)
    {
        $items = self::list();
        $collection = [];

        foreach ($items as $item) {
            if(in_array($item->id, $itemIds)) {
                $collection[] = $item;
            }
        }

        return $collection;
    }
}
