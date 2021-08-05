<?php

namespace App\Core;

class SchemaUtils
{
    /**
     * Return a boolean if entity contains
     * a column in schema
     */
    public static function hasAttribute($attribute, $entity)
    {
        return \Illuminate\Support\Facades\Schema::hasColumn(
            (new $entity)->getTable(),
            $attribute
        );
    }
}
