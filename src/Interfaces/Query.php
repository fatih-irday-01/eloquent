<?php

namespace Fatihirday\Eloquent\Interfaces;

interface Query
{
    public function ifNull(string $column, $value, string $name);

    public function ifCount(string $column, $operator, $value, ?string $name = null);

    public function ifSum(string $column, $operator, $value, string $sumColumn, ?string $name = null);
}
