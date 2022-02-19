<?php

namespace Fatihirday\Eloquent\Pgsql;

use Fatihirday\Eloquent\Interfaces\Query as QueryInterface;

class Query implements QueryInterface
{
    public function ifNull(string $column, $value, string $name)
    {
        return strtr('COALESCE(`{?}`, {:}) as {as}', [
            '{?}' => $column,
            '{:}' => $value,
            '{as}' => $name ?? 'column'
        ]);
    }

    public function ifCount(string $column, $operator, $value, ?string $name = null)
    {
        return strtr('COUNT(`{column}`) FILTER (WHERE `{column}` {operator} {value}) AS {name}', [
            '{column}' => $column,
            '{operator}' => $operator,
            '{value}' => $value,
            '{name}' => $name ?? 'column'
        ]);
    }

    public function ifSum(string $column, $operator, $value, string $sumColumn, ?string $name = null)
    {
        return strtr('SUM(`{sumColumn}`) FILTER (WHERE `{column}` {operator} {value}) AS {name}', [
            '{sumColumn}' => $sumColumn,
            '{column}' => $column,
            '{operator}' => $operator,
            '{value}' => $value,
            '{name}' => $name ?? 'column'
        ]);
    }

}
