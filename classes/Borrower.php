<?php

namespace MediumSans\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;

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
        return DB::table(self::$tableName)->where('id', $id)->delete();

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
        $kirby_uuid = (array_key_exists('kirby_uuid', $input)) ? $input['kirby_uuid'] : $id;
        self::isValid($input);
        $input['updated_at'] = date('Y-m-d H:i:s');
        $query = DB::table(self::$tableName)
                    ->updateOrInsert(['kirby_uuid' => $kirby_uuid], $input);

        return $query;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function isValid(array $input): bool
    {
        $error = false;
        if (V::required($input['firstname']) === false) {
            $error = true;
            throw new InvalidArgumentException(i18n::translate('lendmanagement.error.firstname'));
        }
        if (V::required($input['lastname']) === false) {
            $error = true;
            throw new InvalidArgumentException(i18n::translate('lendmanagement.error.lastname'));
        }
        if (V::required($input['email']) === false) {
            $error = true;
            throw new InvalidArgumentException(i18n::translate('lendmanagement.error.email'));
        }
        if (V::required($input['phone']) === false) {
            $error = true;
            throw new InvalidArgumentException(i18n::translate('lendmanagement.error.phone'));
        }
        return $error;
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
