<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Data\Data;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;


class LendExtension
{
    public static string $tableName = "lend_extensions";

    /**
     * Creates a new lend extension with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool true on success, false on failure
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public static function create(string $lend_id, int $nbrOfDays): bool
    {
        return DB::table(self::$tableName)->insert(
            [
                'lend_id' => $lend_id,
                'nbr_of_days' => $nbrOfDays,
                'created_at' => date('Y-m-d H:i:s'),
                'user' => kirby()->user()->name(),
            ]
        );
    }

    /**
     * Deletes a lend by loanId
     *
     * @param string $id Lend id
     * @return bool true on success, false on failure
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', '=', $id)->delete();
    }

    /**
     * Finds a lend by id and throws an exception
     * if the lend cannot be found
     *
     * @param string $id The id of the lend
     * @return array The lend data
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $lend = static::list()[$id] ?? null;

        if (empty($lend) === true) {
            throw new NotFoundException('The item could not be found');
        }

        return $lend;
    }

    /**
     * Lists all lends from the lends.json
     *
     * @return array The list of lends
     */
    public static function list(): array
    {
        return DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Get the number of pending lends from the lends.json
     *
     * @return int the total of pending lends
     */
    public static function totalPendingLends(): int
    {
        // get all items
        $lends = static::list();
        $pendingLends = 0;

        foreach($lends as $lend) {
            if (!$lend['returnedDate']) {
                $pendingLends++;
            }
        }

        return $pendingLends;
    }

    /**
     * Get the number of late pending lends from the lends.json
     *
     * @return int the total of late and pending lends
     */
    public static function totalLatePendingLends(): int
    {
        // get all items
        $lends = static::list();
        $latePendingLends = 0;

        foreach($lends as $lend) {
            if (!$lend['returnedDate'] && (strtotime($lend['endDate']) < strtotime(date('Y-m-d')))) {
                $latePendingLends++;
            }
        }

        return $latePendingLends;
    }

    /**
     * Get the number of pending and prolonged lends from the lends.json
     *
     * @return int the number of pending and prolonged lends
     */
    public static function totalPendingAndProlongedLends(): int
    {
        // get all items
        $lends = static::list();
        $pendingAndProlongedLends = 0;

        foreach($lends as $lend) {
            if (!$lend['returnedDate']
                && (strtotime($lend['endDate']) < strtotime(date('Y-m-d')))
                && $lend['isProlonged']) {
                $pendingAndProlongedLends++;
            }
        }

        return $pendingAndProlongedLends;
    }

    /**
     * Get the number of lends from the lends.json
     *
     * @return int The number of lends
     */
    public static function count(): int
    {
        // get all items
        $lends = static::list();

        return count($lends);
    }

    /**
     * Updates a lend by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id The id of the lend
     * @param array $input The input data
     * @return boolean True on success, false on failure
     * @throws InvalidArgumentException
     */
    public static function update(string $id, array $input): bool
    {
        $lend = [
            'id'                    => $id,
            'loanId'                => $input['loanId'] ?? null,
            'addedAt'               => $input['startDate'] ?? null,
            'endDate'               => $input['endDate'] ?? null,
            'userId'                => $input['userId'] ?? null,
        ];

        // require a borrower
        if (V::required($input['borrowerId']) === false) {
            throw new InvalidArgumentException('A borrower must be set');
        }

        // load all lends
        $lends = static::list();

        // set/overwrite the item data
        $lends[$id] = $lend;

        return Data::write(static::file(), $lends);
    }

    /**
     * Return a collection of lends from the lends.json
     *
     * @throws NotFoundException
     */
    public static function collection(): array
    {
        $lends = static::list();
        $collection = [];
        foreach ($lends as $lend) {

            $borrower = Borrower::find($lend['borrowerId'][0]);

            $startDate = date_create($lend['startDate']);
            $endDate = date_create($lend['endDate']);

            $nbrObjects = count($lend['itemIds']);
            $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

            $statusColor = (strtotime($lend['endDate']) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

            $collection[] = [
                'text' => $borrower['firstname'] . ' ' . $borrower['lastname'] . ' • ' . $nbrObjects . ' ' . $itemCaption,
                'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate,'d.m.Y'),
                'link' => '/lendmanagement/lend/' . $lend['id'],
                'image' => [
                    'icon' => 'box',
                    'back' => $statusColor
                ]
            ];
        }
        return $collection;
    }

    /**
     * Extend the lend endDate by id with the given input
     *
     * @param string $id The lend id
     * @param int $daysProlonged The number of days to prolong the lend
     *
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public static function extend(string $id, int $daysProlonged): bool
    {
        return static::create($id, $daysProlonged);
    }

    public static function getExtensions(string $lend_id): Array
    {
        $extensions = DB::table(self::$tableName)->where('lend_id', '=', $lend_id)->get()->all();
        return $extensions;
    }

    public static function getLendExpiryDateByLendId(string $end_date, string $lend_id): string
    {
        $lendExpiryDate = '';
        $lendExpiryDate = date_create($end_date);
        $extensions = self::getExtensions($lend_id);

        foreach ($extensions as $extension) {
            $lendExpiryDate = date_add($lendExpiryDate, date_interval_create_from_date_string($extension->nbr_of_days . ' days'));
        }

        return date_format($lendExpiryDate, 'd.m.Y');
    }

    public static function getNumbersofDayAddedInTotalByLendId(string $lend_id): string
    {
        $addedDays = 0;
        $extensions = self::getExtensions($lend_id);

        foreach ($extensions as $extension) {
            $addedDays += $extension->nbr_of_days;
        }

        return $addedDays;
    }
}
