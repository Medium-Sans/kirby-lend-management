<?php

namespace MediumSans\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Exception\NotFoundException;
use Illuminate\Support\Collection;


class Category
{
    public static string $tableName = "categories";

    /**
     * Creates a new category with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        return static::update(uuid(), $input);
    }

    /**
     * Deletes a category by category id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();
    }

    /**
     * Returns the absolute path to the categories.json
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/categories.json';
    }

    /**
     * Finds a category by id and throws an exception
     * if the category cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): Collection
    {
        return DB::table(self::$tableName)->where('id', '=', $id)->get();
    }

    /**
     * Lists all categories from the categories.json
     *
     * @return array
     */
    public static function list(): array
    {
        return DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function count(): int
    {
        $categories = static::list();

        return count($categories);
    }

    /**
     * Updates a category by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $input['kirby_uuid'] = $id;

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

        $categories = self::list();
        $collection = [];

        foreach ($categories as $category) {

            $collection[] = [
                'text' => $category->name,
                'link' => 'lendmanagement/inventory/category/' . $category->id,
                'info' => $category->location ?? '',
                'image' => [
                    'icon' => 'tag',
                    'back' => 'blue-400'
                ]
            ];
        }
        return $collection;
    }

    public static function getOptions(): array
    {
        $categories = self::list();
        $options = [];
        foreach ($categories as $category) {
            $options[] = [
                'text' => $category->name,
                'value' => $category->id,
            ];
        }
        return $options;
    }
}
