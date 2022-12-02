<?php

namespace Kirby\LendManagement;

use chillerlan\QRCode\{QRCode, QROptions};
use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

require_once __DIR__.'/../vendor/autoload.php';

class Item
{

    /**
     * Creates a new item with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        // reuse the update method to create a new
        // item with the new unique id. If you need different logic
        // here, you can easily extend it
        return static::update(uuid(), $input);
    }

    /**
     * Deletes an item by item id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all items
        $items = static::list();

        // remove the item from the list
        unset($items[$id]);

        // write the update list to the file
        return Data::write(static::file(), $items);
    }

    /**
     * Returns the absolute path to the items.json
     * This is the place to modify if you don't want to
     * store the items in your plugin folder
     * – which you probably really don't want to do.
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/items.json';
    }

    /**
     * Finds an item by id and throws an exception
     * if the item cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $item = static::list()[$id] ?? null;

        if (empty($item) === true) {
            throw new NotFoundException('The item could not be found');
        }

        return $item;
    }

    /**
     * Lists all items from the items.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    public static function listAsOptions(): array
    {
        $items = static::list();
        $options = [];

        foreach ($items as $item) {
            $options[] = ['value' => $item->id, 'text' => $item->title ];
        }

        return $options;
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function count(): int
    {
        $items = static::list();

        return count($items);
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function getNumberOfItemsLended(): int
    {
        $items = static::list();
        $numberOfItemsLended = 0;

        foreach($items as $item) {
            if($item['isLended'] === true) {
                $numberOfItemsLended++;
            }
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
     */
    public static function update(string $id, array $input): bool
    {
        $QrCode = new QRCode;

        $item = [
            'id'                 => $id,
            'title'              => $input['title'] ?? null,
            'description'        => $input['description'] ?? null,
            'notes'              => $input['notes'] ?? null,
            'isLended'           => $input['isLended'] ?? null,
            'categoryId'         => $input['categoryId'] ?? null,
            'quantity'           => $input['quantity'] ?? null,
            'nbrOfLoans'         => $input['nbrOfLoans'] ?? null,
            'qrCode'             => $QrCode->render($id),
        ];

        // load all items
        $items = static::list();

        // set/overwrite the item data
        $items[$id] = $item;

        return Data::write(static::file(), $items);
    }

    /**
     * Return a collection of items from in items.json
     *
     * @return array
     * @throws NotFoundException
     */
    public static function collection(): array
    {

        $items = static::list();
        $collection = [];
        foreach ($items as $item) {

            $category = $item['categoryId'] ? Category::find($item['categoryId'])['title'] : '';

            $collection[] = [
                'text' => $item['title'],
                'link' => 'lendmanagement/inventory/item/' . $item['id'],
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
        $items = static::list();
        $ttl = 0;

        foreach ($items as $item) {
            if($item['categoryId'] === $categoryId) {
                $ttl++;
            }
        }

        return $ttl;
    }

    public static function getItemsByCategory(string $categoryId): array
    {
        $items = static::list();
        $collection = [];

        foreach ($items as $item) {
            if($item['categoryId'] === $categoryId) {
                $collection[] = [
                    'text' => $item['title'],
                    'link' => 'lendmanagement/inventory/item/' . $item['id'],
                    'info' => $item['quantity']. " pcs",
                    'image' => [
                        'icon' => 'tag',
                        'back' => 'purple-400'
                    ]
                ];
            }
        }

        return $collection;
    }

    public static function getOptions(): array
    {
        $items = static::list();
        $options = [];
        foreach ($items as $item) {
            $options[] = [
                'text' => $item['title'],
                'value' => $item['id'],
            ];
        }
        return $options;
    }

    public static function getItemsByIds(mixed $itemIds)
    {
        $items = static::list();
        $collection = [];

        foreach ($items as $item) {
            if(in_array($item['id'], $itemIds)) {
                $collection[] = $item;
            }
        }

        return $collection;
    }
}
