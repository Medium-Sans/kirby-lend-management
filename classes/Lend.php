<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;

class Lend
{
    public static string $tableName = "lends";

    /**
     * Creates a new lend with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool true on success, false on failure
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public static function create(array $input): bool
    {

        return self::update(uuid(), $input);
    }

    /**
     * Deletes a lend by loanId
     *
     * @param string $id Lend id
     * @return bool true on success, false on failure
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();
    }

    /**
     * Finds a lend by id and throws an exception
     * if the lend cannot be found
     *
     * @param string $id The id of the lend
     * @return array The lend data
     * @throws NotFoundException
     */
    public static function find(string $id): \stdClass
    {
        $lend = DB::table(self::$tableName)->where('id', $id)->first();

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
        // First thing that will be shown to the user is the lend list.
        // We assume that if something need to be created for the first use
        // of the plugin here is the place to do it
        if (!Database::hasTable(Lend::$tableName)) {
            Database::init();
        };

        $result = DB::table(self::$tableName)->get()->toArray();

        return $result;
    }


    /**
     * Return a list of pending lends
     *
     * @return array list of pending lends
     */
    public static function listOfPendingLends(): array
    {
        // get all items
        $lends = static::list();
        $pendingLends = [];

        foreach ($lends as $lend) {
            if (!$lend->returned_date) {
                $pendingLends[] = $lend;
            }
        }

        return $pendingLends;
    }

    public static function totalPendingLends(): int
    {
        return count(self::listOfPendingLends());
    }

    /**
     * Get the number of late pending lends from the lends.json
     *
     * @return array array of late and pending lends
     */
    public static function listOfLatePendingLends(): array
    {
        // get all items
        $lends = self::list();
        $latePendingLends = [];

        foreach ($lends as $lend) {
            if (!$lend->returned_date && (strtotime($lend->end_date) < strtotime(date('Y-m-d')))) {
                $latePendingLends[] = $lend;
            }
        }

        return $latePendingLends;
    }

    public static function totalLatePendingLends(): int
    {
        return count(self::listOfLatePendingLends());
    }

    /**
     * Get the number of pending and prolonged lends from the lends.json
     *
     * @return array list of pending and prolonged lends
     */
    public static function listOfPendingAndProlongedLends(): array
    {
        // get all items
        $lends = self::list();
        $pendingAndProlongedLends = [];

        foreach ($lends as $lend) {
            if ((!$lend->returned_date)
                && (strtotime($lend->end_date) < strtotime(date('Y-m-d')))
                && $lend->is_prolonged) {
                $pendingAndProlongedLends[] = $lend;
            }
        }

        return $pendingAndProlongedLends;
    }

    public static function totalPendingAndProlongedLends(): int
    {
        return count(self::listOfPendingAndProlongedLends());
    }

    /**
     * Get the number of lends from the lends.json
     *
     * @return int The number of lends
     */
    public static function count(): int
    {
        // get all items
        $lends = self::list();

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
        $input['kirby_uuid'] = $id;
        $input['borrower_id'] = $input['borrower_id'][0] ?? null;

        // The end date must be greater than the start date
        if (V::date($input['end_date'], '>=', $input['start_date']) === false) {
            throw new InvalidArgumentException('The date of return must be greater than the date of lend');
        }

        // require a borrower
        if (V::required($input['borrower_id']) === false) {
            throw new InvalidArgumentException('A borrower must be set');
        }

        // require a item
        if (V::required($input['item_ids']) === false) {
            throw new InvalidArgumentException('An item must be set');
        }

        $user = kirby()->user();

        $lend = DB::table(self::$tableName)->updateOrInsert(
            [
                'kirby_uuid' => $id
            ],
            [
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'borrower_id' => $input['borrower_id'],
                'user' => $user->name(),
            ]);

        if ($lend) {
            $lend_id = DB::getPdo()->lastInsertId();

            foreach ($input['item_ids'] as $itemId) {
                $item = Item::find($itemId);
                DB::table(LendItems::$tableName)->insert([
                    'item_id' => $item[0]->id,
                    'lend_id' => $lend_id,
                ]);
            }
        }

        return $lend;
    }

    /**
     * Return a collection of lends from the lends.json
     *
     * @throws NotFoundException
     */
    public static function collection(): array
    {
        $lends = self::list();
        $collection = [];
        foreach ($lends as $lend) {

            $borrower = Borrower::find($lend->borrower_id);

            $startDate = date_create($lend->start_date);
            $endDate = date_create($lend->end_date);

            $nbrObjects = LendItems::getTotalOfLendedItemsForLend($lend->id);
            $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

            $statusColor = (strtotime($lend->end_date) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

            $collection[] = [
                'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate, 'd.m.Y'),
                'link' => '/lendmanagement/lend/' . $lend->id,
                'image' => [
                    'icon' => 'box',
                    'back' => $statusColor
                ]
            ];
        }
        return $collection;
    }

    public static function getCurrentLends(): array
    {
        $lends = self::list();
        $collection = [];
        foreach ($lends as $lend) {
            if (!$lend->returned_date) {
                $borrower = Borrower::find($lend->borrower_id);

                $startDate = date_create($lend->start_date);
                $endDate = date_create($lend->end_date);

                $nbrObjects = LendItems::getTotalOfLendedItemsForLend($lend->id);
                $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

                $statusColor = (strtotime($lend->end_date) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

                $collection[] = [
                    'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                    'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate, 'd.m.Y'),
                    'link' => '/lendmanagement/lend/' . $lend->id,
                    'image' => [
                        'icon' => 'box',
                        'back' => $statusColor
                    ]
                ];
            }
        }
        return $collection;
    }

    public static function getReturnedLends(): array
    {
        $lends = self::list();
        $collection = [];
        foreach ($lends as $lend) {
            if ($lend->returned_date) {
                $borrower = Borrower::find($lend->borrower_id);

                $startDate = date_create($lend->start_date);
                $endDate = date_create($lend->end_date);

                $nbrObjects = LendItems::getTotalOfLendedItemsForLend($lend->id);
                $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

                $statusColor = 'blue-400';

                $collection[] = [
                    'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                    'info' => 'Retourné le ' . date_format(date_create($lend->returned_date), 'd.m.Y'),
                    'link' => '/lendmanagement/lend/' . $lend->id,
                    'image' => [
                        'icon' => 'box',
                        'back' => $statusColor
                    ]
                ];
            }
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
     * @throws InvalidArgu^mentException
     */
    public static function extend(string $id, int $daysProlonged): bool
    {
        $lend = self::find($id);

        $lend['endDate'] = date('Y-m-d', strtotime($lend['endDate'] . ' + ' . $daysProlonged . ' days'));
        $lend['isProlonged'] = true;
        $lend['nbrOfProlongations']++;

        return self::update($id, $lend);
    }

    public static function return(string $id): bool
    {
        $lend = self::find($id);
        $lend->returned_date = date('Y-m-d');
        $lend->is_returned = 1;

        $result = DB::table(Lend::$tableName)->where('id', $lend->id)->update(
            [
                'returned_date' => date('Y-m-d'),
                'is_returned' => 1,
            ]
        );

        return $result;
    }
}
