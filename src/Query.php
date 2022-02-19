<?php

namespace Fatihirday\Eloquent;

use Fatihirday\Eloquent\Interfaces\Query as QueryInterface;
use Illuminate\Database\Query\Builder;
use Fatihirday\Eloquent\Libraries\Like as Like;

class Query implements QueryInterface
{
    use BaseMethods;

    private Builder $query;

    /**
     * @param $method
     * @param mixed ...$parameters
     * @return Builder
     */
    public function run($method, ...$parameters)
    {
        $driver = ucfirst($this->query->connection->getDriverName());
        $driver = "\Fatihirday\Eloquent\\$driver\Query";

        $result = (new $driver())->$method(... $parameters);
        return $this->query->selectRaw($result);
    }

    /**
     * @param string $column
     * @param $value
     * @param string $name
     * @return Builder
     */
    public function ifNull(string $column, $value, string $name): Builder
    {
        return $this->run(__FUNCTION__, ... func_get_args());
    }

    /**
     * @param string $column
     * @param $operator
     * @param $value
     * @param string|null $name
     * @return Builder
     * @throws \Exception
     */
    public function ifCount(string $column, $operator, $value, ?string $name = null): Builder
    {
        if (is_null($name)) {
            $operator = '=';
        }

        if (!in_array($operator, $this->query->operators)) {
            throw new \Exception('invalid operator');
        }

        return $this->run(__FUNCTION__, $column, $operator, $value, $name);
    }

    /**
     * @param string $column
     * @param $operator
     * @param $value
     * @param string|null $sumColumn
     * @param string|null $name
     * @return $this|Builder
     * @throws \Exception
     */
    public function ifSum(
        string $column,
        $operator,
        $value,
        string $sumColumn = null,
        ?string $name = null
    ): Builder
    {
        if (is_null($name)) {
            $sumColumn = $column;
        }

        if (!in_array($operator, $this->query->operators)) {
            throw new \Exception('invalid operator');
        }

        return $this->run(__FUNCTION__, $column, $operator, $value, $sumColumn, $name);
    }

    /**
     * @param array $caseWhen
     * @param string $name
     * @return Builder
     * @throws \Exception
     */
    public function caseWhen(array $caseWhen, string $name): Builder
    {
        $caseRaw = [];
        $else = 'null';
        foreach ($caseWhen as $when => $then) {
            if (gettype($when) === 'string') {
                $caseRaw[] = join(' ', ['WHEN', $when, 'THEN', $then]);
            }

            if ($when === 0) {
                $else = $then;
            }
        }

        if (empty($caseRaw)) {
            throw new \Exception('caseWhen not nullable');
        }

        array_unshift($caseRaw, 'CASE');
        $caseRaw[] = join(' ', ['ELSE', ($else ?? 'NULL'), 'END', 'as', $name]);

        return $this->query->selectRaw(join(' ', $caseRaw));
    }

    /**
     * @param string $column
     * @param string|null $name
     * @return Builder
     */
    public function sumColumn(string $column, ?string $name = null): Builder
    {
        return $this->query->selectRaw(
            strtr('SUM(`{column}`) as {name}', [
                '{column}' => $column,
                '{name}' => $name ?? 'column'
            ])
        );
    }

    /**
     * @param string $column
     * @param string|null $name
     * @return Builder
     */
    public function countColumn(string $column, ?string $name = null): Builder
    {
        return $this->query->selectRaw(strtr('COUNT(`{column}`) as {name}', [
            '{column}' => $column,
            '{name}' => $name ?? 'column'
        ]));
    }

    public function whereLike(string $column, string $value, ?string $operator = null): Builder
    {
        return $this->query->where(
            $column, 'like', Like::baseLike($value, $operator)
        );
    }

    public function orWhereLike(string $column, string $value, ?string $operator = null): Builder
    {
        return $this->query->orWhere(
            $column, 'like', Like::baseLike($value, $operator)
        );
    }

    public function concat(array $array, string $name, ?string $concatOperator = null): Builder
    {
        $value = !is_null($concatOperator)
            ? join(', ' . "'{$concatOperator}', ", $array)
            : join(', ', $array);

        return $this->query->selectRaw(
            strtr('CONCAT({value}) as {name}', [
                '{value}' => $value,
                '{name}' => $name
            ])
        );
    }
}
