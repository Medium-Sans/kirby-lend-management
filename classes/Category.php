<?php

namespace Kirby\LendManagement;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;

class Category
{

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
        // get all categories
        $categories = static::list();

        // remove the category from the list
        unset($categories[$id]);

        // write the update list to the file
        return Data::write(static::file(), $categories);
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

            $ttlItemsforCategory = Item::getTotalItemsByCategoryId($category['id']);
            $itemsLabel = $ttlItemsforCategory > 1 ? I18n::translate('lendmanagement.items') :
                I18n::translate('lendmanagement.item');

            $collection[] = [
                'text' => $category['title'],
                'link' => '/lendmanagement/inventory/category/' . $category['id'],
                'info' => $ttlItemsforCategory . ' ' . $itemsLabel,
                'image' => [
                    'icon' => 'list-numbers',
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
