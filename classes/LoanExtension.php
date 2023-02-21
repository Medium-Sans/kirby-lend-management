<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Kirby\Data\Data;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;


class LoanExtension
{
    public static string $tableName = "loan_extensions";

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
        // We update the last loan date of the borrower
        (new Borrower)->update($input['borrowerId'][0], ['lastLoanAt' => date('Y-m-d H:i:s')]);

        return static::update(uuid(), $input);
    }

    /**
     * Deletes a loan by loanId
     *
     * @param string $id Loan id
     * @return bool true on success, false on failure
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', '=', $id)->delete();
    }

    /**
     * Finds a loan by id and throws an exception
     * if the loan cannot be found
     *
     * @param string $id The id of the loan
     * @return array The loan data
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $loan = static::list()[$id] ?? null;

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
        return DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Get the number of pending loans from the loans.json
     *
     * @return int the total of pending loans
     */
    public static function totalPendingLoans(): int
    {
        // get all items
        $loans = static::list();
        $pendingLoans = 0;

        foreach($loans as $loan) {
            if (!$loan['returnedDate']) {
                $pendingLoans++;
            }
        }

        return $pendingLoans;
    }

    /**
     * Get the number of late pending loans from the loans.json
     *
     * @return int the total of late and pending loans
     */
    public static function totalLatePendingLoans(): int
    {
        // get all items
        $loans = static::list();
        $latePendingLoans = 0;

        foreach($loans as $loan) {
            if (!$loan['returnedDate'] && (strtotime($loan['endDate']) < strtotime(date('Y-m-d')))) {
                $latePendingLoans++;
            }
        }

        return $latePendingLoans;
    }

    /**
     * Get the number of pending and prolonged loans from the loans.json
     *
     * @return int the number of pending and prolonged loans
     */
    public static function totalPendingAndProlongedLoans(): int
    {
        // get all items
        $loans = static::list();
        $pendingAndProlongedLoans = 0;

        foreach($loans as $loan) {
            if (!$loan['returnedDate']
                && (strtotime($loan['endDate']) < strtotime(date('Y-m-d')))
                && $loan['isProlonged']) {
                $pendingAndProlongedLoans++;
            }
        }

        return $pendingAndProlongedLoans;
    }

    /**
     * Get the number of loans from the loans.json
     *
     * @return int The number of loans
     */
    public static function count(): int
    {
        // get all items
        $loans = static::list();

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
        $loan = [
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

        // load all loans
        $loans = static::list();

        // set/overwrite the item data
        $loans[$id] = $loan;

        return Data::write(static::file(), $loans);
    }

    /**
     * Return a collection of loans from the loans.json
     *
     * @throws NotFoundException
     */
    public static function collection(): array
    {
        $loans = static::list();
        $collection = [];
        foreach ($loans as $loan) {

            $borrower = Borrower::find($loan['borrowerId'][0]);

            $startDate = date_create($loan['startDate']);
            $endDate = date_create($loan['endDate']);

            $nbrObjects = count($loan['itemIds']);
            $itemCaption = $nbrObjects > 1 ? i18n::translate('lendmanagement.items') : i18n::translate('lendmanagement.item');

            $statusColor = (strtotime($loan['endDate']) < strtotime(date('Y-m-d'))) ? 'red-400' : 'green-400';

            $collection[] = [
                'text' => $borrower['firstname'] . ' ' . $borrower['lastname'] . ' • ' . $nbrObjects . ' ' . $itemCaption,
                'info' => date_format($startDate, 'd.m.Y') . ' / ' . date_format($endDate,'d.m.Y'),
                'link' => '/lendmanagement/loan/' . $loan['id'],
                'image' => [
                    'icon' => 'box',
                    'back' => $statusColor
                ]
            ];
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
     * @throws InvalidArgumentException
     */
    public static function extend(string $id, int $daysProlonged): bool
    {
        $loan = static::find($id);

        $loan['endDate'] = date('Y-m-d', strtotime($loan['endDate'] . ' + ' . $daysProlonged . ' days'));
        $loan['isProlonged'] = true;
        $loan['nbrOfProlongations']++;

        return static::update($id, $loan);
    }
}
