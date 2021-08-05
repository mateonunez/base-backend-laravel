<?php

namespace App\Core;

class Helper
{
    /**
     * Returns a boolean value indicating whether the class uses some trait
     *
     * @param mixed $class
     * @param mixed $trait
     *
     * @return bool
     */
    public static function classUsesTrait($class, $trait): bool
    {
        return in_array(
            $trait,
            class_uses_recursive($class),
            true
        );
    }
}
