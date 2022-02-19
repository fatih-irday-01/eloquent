<?php

namespace Fatihirday\Eloquent;

trait BaseMethods
{
    public function builder($builder)
    {
        $this->query = $builder;
        return $this;
    }
}
