<?php

namespace Fatihirday\Eloquent\Providers\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CustomEloquentServiceProvider
{
    private function getArgs($funtion): string
    {
        $parameters = $funtion->getParameters();

        if (empty($parameters)) {
            return '';
        }

        $args = array_map(function ($item) {
            $type = '';
            if ($item->getType()) {
                $allowsNull = '';
                if ($item->getType()->isBuiltin()) {
                    $allowsNull = $item->getType()->allowsNull() ? '?' : '';
                }
                $type = $allowsNull . $item->getType()->getName();
            }

            $defaultValue = '';
            if ($item->isOptional()) {
                $defaultValue = '= ' . ($item->getDefaultValue() ? $item->getDefaultValue() : 'null');
            }

            return trim(strtr('{type} ${name} {defaultValue}', [
                '{type}' => $type,
                '{name}' => $item->getName(),
                '{defaultValue}' => $defaultValue,
            ]));
        }, $parameters);

        return implode(', ', $args);
    }

    private function addMacros($query, $builder)
    {
        $query = new $query();

        foreach (array_diff(get_class_methods($query), ['builder', 'run']) as $method) {
            $funtion = new \ReflectionMethod($query, $method);
            $args = $this->getArgs($funtion);
            $returnType = $funtion->getReturnType();
            $return = !empty($returnType) ? $returnType->__toString() : '';
            if (!empty($return)) {
                $return = strstr($return, '\\') ? ':\\' . $return : ':' . $return;
            }

            $add = "/**
             * @return $builder
             * @throws \Exception
             * @instantiated
             */
             \$builder::macro(\$method, function ($args) use (\$query, \$method) $return {
                if (func_num_args() > 0) {
                    return \$query->builder(\$this)->\$method(... func_get_args());
                }
                return \$query->builder(\$this)->\$method();
            });";

            eval($add);
        }
    }

    public function getEloquent()
    {
        $this->addMacros(\Fatihirday\Eloquent\Eloquent::class, EloquentBuilder::class);
        $this->addMacros(\Fatihirday\Eloquent\Query::class, QueryBuilder::class);
    }
}
