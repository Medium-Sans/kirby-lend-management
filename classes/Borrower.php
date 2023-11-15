<?php

namespace MediumSans\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;

class Borrower
{
    public static string $tableName = "borrowers";

    /**
     * Creates a new borrower with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public static function create(array $input): bool
    {
        return self::update(uuid(), $input);
    }

    /**
     * Deletes a borrower by borrower id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();

    }

    /**
     * Finds a borrower by id and throws an exception
     * if the borrower cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): \stdClass
    {
        $borrower = DB::table(self::$tableName)->where('id', $id)->first();

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
        return (array)DB::table(self::$tableName)->get()->toArray();
    }

    public static function listWithLastLend(): array
    {
        $borrowers = (array)DB::table(self::$tableName)->get()->toArray();
        $lends = Lend::list();

        foreach ($borrowers as $borrower) {

            $borrower->lastLend = null;

            foreach ($lends as $lend) {
                if ($lend->borrower_id === $borrower->id) {
                    $borrower->lastLend = $lend->end_date;
                }
            }
        }

        return $borrowers;
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
        $input['kirby_uuid'] = $id;

        return DB::table(self::$tableName)->updateOrInsert(
            ['kirby_uuid' => $id],
            $input);
    }

    public static function getOptions(): array
    {
        $borrowers = static::list();
        $options = [];
        foreach ($borrowers as $borrower) {
            $options[] = [
                'text' => $borrower->firstname . ' ' . $borrower->lastname . ' - ' . $borrower->email,
                'value' => $borrower->id,
            ];
        }
        return $options;
    }
}
