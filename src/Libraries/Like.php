<?php

namespace Fatihirday\Eloquent\Libraries;

use Fatihirday\Eloquent\Libraries\Enums\Like as LikeEnum;

class Like extends LikeEnum
{
    public static function baseLike(string $value, $operator): string
    {
        switch ($operator) {
            case self::FIRST:
                $format = '%%%s';
                break;
            case self::MIDDLE:
                $format = '%%%s%%';
                break;
            case self::LAST:
                $format = '%s%%';
                break;
            default:
                $format = '%s';
        }

        return sprintf($format, $value);
    }
}
