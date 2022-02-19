<?php

namespace Fatihirday\Eloquent;

use Illuminate\Database\Eloquent\Builder;

class Eloquent
{
    use BaseMethods;

    private Builder $query;

    /**
     * @return string
     */
    public function getSql(): string
    {
        $bindings = array_map(function ($item) {
            if (gettype($item) == 'string') {
                $item = "'{$item}'";
            }
            return $item;
        }, $this->query->getBindings());

        $sql = str_replace('?', "%s", $this->query->toSql());

        return vsprintf($sql, $bindings);
    }

    public function dumpSql()
    {
        dump($this->getSql());
    }

    public function ddSql()
    {
        dd($this->getSql());
    }
}
