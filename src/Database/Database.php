<?php

namespace Sweet1s\MoonShineDatabase\Database;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

class Database
{
    /**
     * @var DoctrineTable
     */
    protected DoctrineTable $table;
    /**
     * @var DoctrineTable
     */
    protected DoctrineTable $tableDiff;


    /**
     * @return AbstractSchemaManager
     */
    public static function manager(): AbstractSchemaManager
    {
        return self::connection()->getDoctrineSchemaManager();
    }


    /**
     * @throws Exception
     */
    public static function platform(): ?\Doctrine\DBAL\Platforms\AbstractPlatform
    {
        return self::connection()->getDoctrineConnection()->getDatabasePlatform();
    }

    /**
     * @throws Exception
     */
    public static function createTable($request): void
    {
        try {
            self::manager()->createTable(Table::make($request));
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function update($request): void
    {
        try {
            $updater = new self();
            $updater->setTables($request);
            $updater->updateTable();
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function destroy($tableName): void
    {
        try {
            self::manager()->dropTable($tableName);
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    protected function setTables($request): void
    {
        try {
            $tableNames = array_shift($request);

            $this->table = self::manager()->introspectTable($tableNames['name_old']);
            $this->tableDiff = new DoctrineTable(
                $tableNames['name'],
                Column::make($request),
                Index::make($request),
                $this->table->getUniqueConstraints(),
                $this->table->getForeignKeys(),
                $this->table->getOptions()
            );
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    protected function updateTable(): void
    {
        try {
            $tableDiff = (new Comparator())->compareTables($this->table, $this->tableDiff);
            self::manager()->alterTable($tableDiff);

            if ($this->table->getName() !== $this->tableDiff->getName()) {
                self::manager()->renameTable($this->table->getName(), $this->tableDiff->getName());
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @return Connection
     */
    protected static function connection(): Connection
    {
        return DB::connection();
    }
}
