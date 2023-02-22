<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;

class Loan
{
    public static string $tableName = "loans";

    /**
     * Creates a new loan with the given $input
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
     * Deletes a loan by loanId
     *
     * @param string $id Loan id
     * @return bool true on success, false on failure
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();
    }

    /**
     * Finds a loan by id and throws an exception
     * if the loan cannot be found
     *
     * @param string $id The id of the loan
     * @return array The loan data
     * @throws NotFoundException
     */
    public static function find(string $id): \stdClass
    {
        $loan = DB::table(self::$tableName)->where('id', $id)->first();

        if (empty($loan) === true) {
            throw new NotFoundException('The item could not be found');
        }

        return $loan;
    }

    /**
     * Lists all loans from the loans.json
     *
     * @return array The list of loans
     */
    public static function list(): array
    {
        // First thing that will be shown to the user is the loan list.
        // We assume that if something need to be created for the first use
        // of the plugin here is the place to do it
        if (!Database::hasTable(Loan::$tableName)) {
            Database::init();
        };

        $result = DB::table(self::$tableName)->get()->toArray();

        return $result;
    }


    /**
     * Return a list of pending loans
     *
     * @return array list of pending loans
     */
    public static function listOfPendingLoans(): array
    {
        // get all items
        $loans = static::list();
        $pendingLoans = [];

        foreach ($loans as $loan) {
            if (!$loan->returned_date) {
                $pendingLoans[] = $loan;
            }
        }

        return $pendingLoans;
    }

    public static function totalPendingLoans(): int
    {
        return count(self::listOfPendingLoans());
    }

    /**
     * Get the number of late pending loans from the loans.json
     *
     * @return array array of late and pending loans
     */
    public static function listOfLatePendingLoans(): array
    {
        // get all items
        $loans = self::list();
        $latePendingLoans = [];

        foreach ($loans as $loan) {
            if (!$loan->returned_date && (strtotime($loan->end_date) < strtotime(date('Y-m-d')))) {
                $latePendingLoans[] = $loan;
            }
        }

        return $latePendingLoans;
    }

    public static function totalLatePendingLoans(): int
    {
        return count(self::listOfLatePendingLoans());
    }

    /**
     * Get the number of pending and prolonged loans from the loans.json
     *
     * @return array list of pending and prolonged loans
     */
    public static function listOfPendingAndProlongedLoans(): array
    {
        // get all items
        $loans = self::list();
        $pendingAndProlongedLoans = [];

        foreach ($loans as $loan) {
            if ((!$loan->returned_date)
                && (strtotime($loan->end_date) < strtotime(date('Y-m-d')))
                && $loan->is_prolonged) {
                $pendingAndProlongedLoans[] = $loan;
            }
        }

        return $pendingAndProlongedLoans;
    }

    public static function totalPendingAndProlongedLoans(): int
    {
        return count(self::listOfPendingAndProlongedLoans());
    }

    /**
     * Get the number of loans from the loans.json
     *
     * @return int The number of loans
     */
    public static function count(): int
    {
        // get all items
        $loans = self::list();

        return count($loans);
    }

    /**
     * Updates a loan by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id The id of the loan
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
            throw new InvalidArgumentException('The date of return must be greater than the date of loan');
        }

        // require a borrower
        if (V::required($input['borrower_id']) === false) {
            throw new InvalidArgumentException('A borrower must be set');
        }

        // require a item
        if (V::required($input['item_ids']) === false) {
            throw new InvalidArgumentException('An item must be set');
        }

        $loan = DB::table(self::$tableName)->updateOrInsert(
            [
                'kirby_uuid' => $id
            ],
            [
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'borrower_id' => $input['borrower_id'],
            ]);

        if ($loan) {
            $loan_id = DB::getPdo()->lastInsertId();

            foreach ($input['item_ids'] as $itemId) {
                $item = Item::find($itemId);
                DB::table(LoanItems::$tableName)->insert([
                    'item_id' => $item[0]->id,
                    'loan_id' => $loan_id,
                ]);
            }
        }

        return $loan;
    }

    /**
     * Return a collection of loans from the loans.json
     *
     * @throws NotFoundException
     */
    public static function collection(): array
    {
        $loans = self::list();
        $collection = [];
        foreach ($loans as $loan) {

            $borrower = Borrower::find($loan->borrower_id);

            $startDate = date_create($loan->start_date);
            $endDate = date_create($loan->end_date);

            $nbrObjects = LoanItems::getTotalOfLendedItemsForLoan($loan->id);
            $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

            $statusColor = (strtotime($loan->end_date) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

            $collection[] = [
                'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate, 'd.m.Y'),
                'link' => '/lendmanagement/loan/' . $loan->id,
                'image' => [
                    'icon' => 'box',
                    'back' => $statusColor
                ]
            ];
        }
        return $collection;
    }

    public static function getCurrentLoans(): array
    {
        $loans = self::list();
        $collection = [];
        foreach ($loans as $loan) {
            if (!$loan->returned_date) {
                $borrower = Borrower::find($loan->borrower_id);

                $startDate = date_create($loan->start_date);
                $endDate = date_create($loan->end_date);

                $nbrObjects = LoanItems::getTotalOfLendedItemsForLoan($loan->id);
                $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

                $statusColor = (strtotime($loan->end_date) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

                $collection[] = [
                    'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                    'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate, 'd.m.Y'),
                    'link' => '/lendmanagement/loan/' . $loan->id,
                    'image' => [
                        'icon' => 'box',
                        'back' => $statusColor
                    ]
                ];
            }
        }
        return $collection;
    }

    public static function getReturnedLoans(): array
    {
        $loans = self::list();
        $collection = [];
        foreach ($loans as $loan) {
            if ($loan->returned_date) {
                $borrower = Borrower::find($loan->borrower_id);

                $startDate = date_create($loan->start_date);
                $endDate = date_create($loan->end_date);

                $nbrObjects = LoanItems::getTotalOfLendedItemsForLoan($loan->id);
                $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

                $statusColor = 'blue-400';

                $collection[] = [
                    'text' => $borrower->firstname . ' ' . $borrower->lastname . ' • ' . $nbrObjects . ' ' . $itemCaption,
                    'info' => 'Retourné le ' . date_format(date_create($loan->returned_date), 'd.m.Y'),
                    'link' => '/lendmanagement/loan/' . $loan->id,
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
     * Extend the loan endDate by id with the given input
     *
     * @param string $id The loan id
     * @param int $daysProlonged The number of days to prolong the loan
     *
     * @throws NotFoundException
     * @throws InvalidArgu^mentException
     */
    public static function extend(string $id, int $daysProlonged): bool
    {
        $loan = self::find($id);

        $loan['endDate'] = date('Y-m-d', strtotime($loan['endDate'] . ' + ' . $daysProlonged . ' days'));
        $loan['isProlonged'] = true;
        $loan['nbrOfProlongations']++;

        return self::update($id, $loan);
    }

    public static function return(string $id): bool
    {
        $loan = self::find($id);
        $loan->returned_date = date('Y-m-d');
        $loan->is_returned = 1;

        $result = DB::table(Loan::$tableName)->where('id', $loan->id)->update(
            [
                'returned_date' => date('Y-m-d'),
                'is_returned' => 1,
            ]
        );

        return $result;
    }
}
