<?php

namespace MediumSans\LendManagement;

use Beebmx\KirbyDb\DB;

class LendItems
{
    public static string $tableName = "lend_items";

    public static function getTotalOfLendedItems() {
        return DB::table(self::$tableName)->count();
    }

    public static function getTotalOfLendedItemsForLend($id) {
        $result = DB::table(self::$tableName)->where('lend_id', $id)->count();
        return $result;
    }

    public static function getItemsbyLend($id) {
        $result = DB::table(self::$tableName)->where('lend_id', $id)->get();
        $items = [];
        foreach ($result as $item) {
            $items[] = $item->item_id;
        }
        return $items;
    }
}
