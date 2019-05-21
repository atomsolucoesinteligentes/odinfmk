<?php

namespace Freya\orm;

use Odin\utils\Enum;

final class Operator extends Enum
{
    const GREATER = ">";
    const GREATER_OR_EQUALS = ">=";
    const LESS = "<";
    const LESS_OR_EQUALS = "<=";
    const EQUALS = "=";
    const BETWEEN = "BETWEEN";
    const NOT_BETWEEN = "NOT BETWEEN";
    const LIKE = "LIKE";
    const LIKE_R = "LIKE_L";
    const LIKE_L = "LIKE_R";
    const IN = "IN";
    const NOT_IN = "NOT IN";
    const IS = "IS";
    const IS_NOT = "IS NOT";
}
