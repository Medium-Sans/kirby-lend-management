<?php

namespace Kirby\LendManagement;

use DateTimeZone;
use Kirby\Data\Data;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;

class Loan
{

    /**
     * Creates a new loan with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function create(array $input): bool
    {
        // We update the last loan date of the borrower
        Borrower::update($input['borrowerId'][0], ['lastLoanAt' => date('Y-m-d H:i:s')]);

        return static::update(uuid(), $input);
    }

    /**
     * Deletes a loan by loanId
     *
     * @param string $id
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
     * Returns the absolute path to the loans.json
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/loans.json';
    }

    /**
     * Finds a loan by id and throws an exception
     * if the loan cannot be found
     *
     * @param string $id
     * @return array
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
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Get the number of pending loans from the loans.json
     *
     * @return int
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
     * @return int
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
     * @return int
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
     * @return int
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
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $loan = [
            'id'                    => $id,
            'startDate'             => $input['startDate'] ?? null,
            'endDate'               => $input['endDate'] ?? null,
            'borrowerId'            => $input['borrowerId'] ?? null,
            'itemIds'               => $input['itemIds'] ?? null,
            'nbrOfProlongations'    => $input['nbrOfProlongations'] ?? null,
            'isProlonged'           => $input['isProlonged'] ?? null,
            'returnedDate'          => $input['returnedDate'] ?? null,
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
}
