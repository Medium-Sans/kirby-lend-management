<?php

namespace Kirby\LendManagement;

use Kirby\Data\Data;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\V;

class Borrower
{

    /**
     * Creates a new item with the given $input
     * data and adds it to the json file
     *
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
     * Deletes a item by item id
     *
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
        return __DIR__ . '/../data/borrowers.json';
    }

    /**
     * Finds a item by id and throws an exception
     * if the item cannot be found
     *
     * @param string $id
     * @return array
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

    /**
     * Updates a item by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $item = [
            'id'            => $id,
            'firstname'     => $input['firstname'] ?? null,
            'lastname'      => $input['lastname'] ?? null,
            'email'         => $input['email'] ?? null,
            'phone'         => $input['phone'] ?? null,
            'notes'         => $input['notes'] ?? null,
            'lastloan'      => $input['lastloan'] ?? null,
        ];

        // require an email
        if (V::minlength($item['email'], 1) === false) {
            throw new InvalidArgumentException('The email must not be empty');
        }

        // load all items
        $items = static::list();

        // set/overwrite the item data
        $items[$id] = $item;

        return Data::write(static::file(), $items);
    }

    public static function getOptions(): array
    {
        $borrowers = static::list();
        $options = [];
        foreach ($borrowers as $borrower) {
            $options[] = [
                'text' => $borrower['firstname'] . ' ' . $borrower['lastname'] . ' - ' . $borrower['email'],
                'value' => $borrower['id'],
            ];
        }
        return $options;
    }
}
