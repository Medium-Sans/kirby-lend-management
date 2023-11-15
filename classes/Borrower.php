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
        $input['created_at'] = date('Y-m-d H:i:s');
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
     * Lists all borrowers
     *
     * @return array
     */
    public static function list(): array
    {
        return (array)DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Lists all borrowers with the last lend date
     * @return array
     */
    public static function listWithLastLend(): array
    {
        $query = DB::table(self::$tableName)
            ->leftJoin('lends', self::$tableName.'.id', '=', 'lends.borrower_id')
            ->select(self::$tableName.'.*', DB::raw('MAX(lends.end_date) as lastLend'))
            ->groupBy(self::$tableName.'.id')
            ->get()
            ->toArray();
        return $query;
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
        $input['updated_at'] = date('Y-m-d H:i:s');
        $query = DB::table(self::$tableName)
                    ->updateOrInsert(['kirby_uuid' => $input['kirby_uuid']], $input);

        return $query;
    }

    /**
     * @return array
     */
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
