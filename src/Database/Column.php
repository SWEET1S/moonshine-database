<?php

namespace Sweet1s\MoonShineDatabase\Database;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Doctrine\DBAL\Schema\SchemaException;

class Column
{

    /**
     * @throws SchemaException
     */
    public static function make($columns): array
    {
        return (new self())->getColumns($columns);
    }

    /**
     * @throws SchemaException
     */
    protected function getColumns($columns): array
    {
        try {
            $result = [];

            foreach ($columns as $column) {
                $columnDBAL = new DoctrineColumn(
                    $column['name'],
                    new $column['type']()
                );

                $columnDBAL->setLength($column['length']);
                $columnDBAL->setNotnull(self::checkboxToBoolean($column['not_null']));
                $columnDBAL->setUnsigned(self::checkboxToBoolean($column['unsigned']));
                $columnDBAL->setAutoincrement(self::checkboxToBoolean($column['auto_inc']));
                $columnDBAL->setDefault($column['by_default']);

                $result[] = $columnDBAL;
            }

            return $result;
        } catch (Exception $exception) {
            throw new SchemaException($exception->getMessage());
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected static function checkboxToBoolean($value): bool
    {
        return ($value == 'on' || $value == '1') ?? false;
    }
}
