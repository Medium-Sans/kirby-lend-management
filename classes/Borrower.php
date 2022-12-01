<?php

namespace Kirby\LendManagement;

use Kirby\Data\Data;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\V;

class Borrower
{

    /**
     * Creates a new borrower with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function create(array $input): bool
    {
        return static::update(uuid(), $input);
    }

    /**
     * Deletes a borrower by borrower id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all borrowers
        $borrowers = static::list();

        // remove the borrower from the list
        unset($borrowers[$id]);

        // write the update list to the file
        return Data::write(static::file(), $borrowers);
    }

    /**
     * Returns the absolute path to the borrowers.json
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/borrowers.json';
    }

    /**
     * Finds a borrower by id and throws an exception
     * if the borrower cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $borrower = static::list()[$id] ?? null;

        if (empty($borrower) === true) {
            throw new NotFoundException('The borrower could not be found');
        }

        return $borrower;
    }

    /**
     * Lists all borrowers from the borrowers.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Updates a borrower by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public static function update(string $id, array $input): bool
    {
        try {
            $borrower = static::find($id);
        } catch (NotFoundException $e) {
            $borrower = [];
        }

        $updatedBorrower = [
            'id'            => $id,
            'firstname'     => $input['firstname'] ?? ($borrower['firstname'] ?? ''),
            'lastname'      => $input['lastname'] ?? ($borrower['lastname'] ?? ''),
            'email'         => $input['email'] ?? ($borrower['email'] ?? ''),
            'phone'         => $input['phone'] ?? ($borrower['phone'] ?? ''),
            'notes'         => $input['notes'] ?? ($borrower['notes'] ?? ''),
            'lastLoanAt'    => $input['lastLoanAt'] ?? ($borrower['lastLoanAt'] ?? ''),
        ];

        // require an email
        if (V::minlength($updatedBorrower['firstname'], 1) === false) {
            throw new InvalidArgumentException('The email must not be empty');
        }

        // load all borrowers
        $borrowers = static::list();

        // set/overwrite the borrower data
        $borrowers[$id] = $updatedBorrower;

        return Data::write(static::file(), $borrowers);
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
