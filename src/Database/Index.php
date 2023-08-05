<?php

namespace Sweet1s\MoonShineDatabase\Database;

use Doctrine\DBAL\Schema\Index as DoctrineIndex;

class Index
{

    /**
     * @param $columns
     * @return array
     */
    public static function make($columns): array
    {
        return (new self())->getIndexes($columns);
    }

    /**
     * @param $columns
     * @return array
     */
    protected function getIndexes($columns): array
    {
        $indexes = [];
        foreach ($columns as $column) {
            if ($column['index'] == 'PRIMARY') {
                $indexes[$column['name']] = new DoctrineIndex(
                    $column['name'] . "_" . $column['index'],
                    [$column['name']],
                    true,
                    true
                );
            }

            if ($column['index'] == 'UNIQUE') {
                $indexes[$column['name']] = new DoctrineIndex(
                    $column['name'] . "_" . $column['index'],
                    [$column['name']],
                    true,
                    false
                );
            }

            if ($column['index'] == 'INDEX') {
                $indexes[$column['name']] = new DoctrineIndex(
                    $column['name'] . "_" . $column['index'],
                    [$column['name']],
                    false,
                    false
                );
            }
        }

        return $indexes;
    }
}
