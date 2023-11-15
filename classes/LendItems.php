<?php

namespace MediumSans\LendManagement;

use Beebmx\KirbyDb\DB;

class LendItems
{
    public static string $tableName = "lend_items";

    /**
     * @return mixed
     */
    public static function getTotalOfLendedItems()
    {
        return DB::table(self::$tableName)->count();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getTotalOfLendedItemsForLend($id)
    {
        $result = DB::table(self::$tableName)->where('lend_id', $id)->count();
        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getItemsByLend($id): array {
        return DB::table(self::$tableName)
            ->where('lend_id', $id)
            ->get(['item_id as id', 'quantity'])
            ->toArray();
    }
}
