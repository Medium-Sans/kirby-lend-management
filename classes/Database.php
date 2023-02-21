<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\Schema;
use Kirby\Filesystem\F;
use Kirby\Exception\InvalidArgumentException;

require_once __DIR__.'/../vendor/autoload.php';

class Database
{

    /**
     * Creates the database schema
     *
     * @return bool Returns true if the schema was created
     */
    static function createSchema() : bool
    {
        // Borrowers
        Schema::create(Borrower::$tableName, function($table)
        {
            $table->increments('id');
            $table->string('kirby_uuid');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // Categories
        Schema::create(Category::$tableName, function($table)
        {
            $table->increments('id');
            $table->string('kirby_uuid');
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        // Items
        Schema::create(Item::$tableName, function($table)
        {
            $table->increments('id');
            $table->string('kirby_uuid');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
            $table->string('quantity')->nullable();
            $table->string('current_quantity')->nullable();
            $table->string('qr_code')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });

        // Loans
        Schema::create(Loan::$tableName, function ($table) {
            $table->increments('id');
            $table->string('kirby_uuid');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('borrower_id')->unsigned();
            $table->boolean('is_returned')->default(false);
            $table->date('returned_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // Loan Items
        Schema::create(LoanItems::$tableName, function ($table) {
            $table->increments('id');
            $table->integer('loan_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('loan_id')->references('id')->on('loans');
        });

        // Loan Extensions
        Schema::create(LoanExtension::$tableName, function($table)
        {
            $table->increments('id');
            $table->string('kirby_uuid');
            $table->integer('loan_id')->unsigned();
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->integer('nbr_of_days');
            $table->timestamps();
        });

        return static::hasTable(Borrower::$tableName);
    }

    /**
     * Check if the table exists
     *
     * @param string $tableName the name of the table to check
     * @return bool
     */
    static function hasTable(string $tableName) : bool
    {
        return Schema::hasTable($tableName);
    }

    /**
     * Check if the database file exists
     *
     * @return bool
     */
    static function exist() : bool {
        return F::exists(self::getPath());
    }

    /**
     * Create the database file
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    static function create() : bool {

        $databaseFilePath = self::getPath();

        if ($databaseFilePath === null) {
            throw new InvalidArgumentException('Please set the path of your database on the config file.');
        }

        return F::create(self::getPath());
    }

    /**
     * Check if the database file exist and the schema is initialized
     * -> if the database file doesn't exist, create it and initialize the schema
     * -> if the dabase file exist, initialize the schema
     *
     * @return bool Returns true if the database is initialized
     */
    static function init() : bool
    {
        $dbFileExist = self::exist();
        $dbSchemaExist = self::hasTable(Borrower::$tableName);

        if(!$dbFileExist) {
            self::create();
            self::createSchema();
        }

        if($dbFileExist && !$dbSchemaExist) {
            self::createSchema();
        }

        return self::hasTable(Borrower::$tableName);
    }

    static function getPath() : string {
        return option('beebmx.kirby-db.drivers')['sqlite']['database'];
    }
}
