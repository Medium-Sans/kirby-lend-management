<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;

class LoanItems
{
    public static string $tableName = "loans_items";

    public static function getTotalOfLendedItems() {
        return DB::table(self::$tableName)->count();
    }

    public static function getTotalOfLendedItemsForLoan($id) {
        $result = DB::table(self::$tableName)->where('loan_id', $id)->count();
        return $result;
    }

    public static function getItemsbyLoan($id) {
        $result = DB::table(self::$tableName)->where('loan_id', $id)->get();
        $items = [];
        foreach ($result as $item) {
            $items[] = $item->item_id;
        }
        return $items;
    }
}
