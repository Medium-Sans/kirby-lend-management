<?php

namespace Kirby\LendManagement;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Category
{

    /**
     * Creates a new category with the given $input
     * data and adds it to the json file
     *
     * @return bool
     */
    public static function create(array $input): bool
    {
        // reuse the update method to create a new
        // category with the new unique id. If you need different logic
        // here, you can easily extend it
        return static::update(uuid(), $input);
    }

    /**
     * Deletes a category by category id
     *
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all categories
        $categories = static::list();

        // remove the category from the list
        unset($categories[$id]);

        // write the update list to the file
        return Data::write(static::file(), $categories);
    }

    /**
     * Returns the absolute path to the categories.json
     * This is the place to modify if you don't want to
     * store the categories in your plugin folder
     * – which you probably really don't want to do.
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
     */
    public static function find(string $id): array
    {
        $category = static::list()[$id] ?? null;

        if (empty($category) === true) {
            throw new NotFoundException('The category could not be found');
        }

        return $category;
    }

    /**
     * Lists all categories from the categories.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
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
     * Lists all available category types
     *
     * @return array
     */
    public static function types(): array
    {
        return [
            'bakery',
            'dairy',
            'fruit',
            'meat',
            'vegan',
            'vegetable',
        ];
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
        $category = [
            'id'          => $id,
            'title'       => $input['title'] ?? null,
            'description' => $input['description'] ?? null,
            'location'    => $input['location'] ?? null,
        ];

        // load all categories
        $categories = static::list();

        // set/overwrite the category data
        $categories[$id] = $category;

        return Data::write(static::file(), $categories);
    }

    public static function collection(): array
    {
        $categories = static::list();
        $collection = [];
        foreach ($categories as $category) {
            $collection[] = [
                'text' => $category['title'],
                'link' => '/lendmanagement/inventory/category/' . $category['id'],
                'image' => [
                    'icon' => 'cart',
                    'back' => 'purple-400'
                ]
            ];
        }
        return $collection;
    }

    public static function getOptions(): array
    {
        $categories = static::list();
        $options = [];
        foreach ($categories as $category) {
            $options[] = [
                'text' => $category['title'],
                'value' => $category['id'],
            ];
        }
        return $options;
    }
}
