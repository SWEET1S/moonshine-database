<?php

namespace Sweet1s\MoonShineDatabase\Database;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table as DoctrineTable;

class Table
{
    /**
     * @throws SchemaException
     * @throws Exception
     */
    public static function make($request): DoctrineTable
    {
        return new DoctrineTable(
            array_shift($request)['name'],
            Column::make($request),
            Index::make($request)
        );
    }
}
