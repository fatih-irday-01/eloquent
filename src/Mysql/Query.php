<?php

namespace Fatihirday\Eloquent\Mysql;

use Fatihirday\Eloquent\Interfaces\Query as QueryInterface;

class Query implements QueryInterface
{
    public function ifNull(string $column, $value, string $name)
    {
        return strtr('IFNULL(`{?}`, {:}) as {as}', [
            '{?}' => $column,
            '{:}' => $value,
            '{as}' => $name ?? 'column'
        ]);
    }

    public function ifCount(string $column, $operator, $value, ?string $name = null)
    {
        return strtr('SUM(if(`{column}` {operator} {value}, 1, 0)) as {name}', [
            '{column}' => $column,
            '{operator}' => $operator,
            '{value}' => $value,
            '{name}' => $name ?? 'column'
        ]);
    }

    public function ifSum(string $column, $operator, $value, string $sumColumn, ?string $name = null)
    {
        return strtr('SUM(if(`{column}` {operator} {value}, {sumColumn}, 0)) as {name}', [
            '{sumColumn}' => $sumColumn ?? $column,
            '{column}' => $column,
            '{operator}' => $operator,
            '{value}' => $value,
            '{name}' => $name ?? 'column'
        ]);
    }
}
