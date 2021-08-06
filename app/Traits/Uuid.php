<?php

namespace App\Traits;

trait Uuid
{

    /**
     * Boot method
     *
     * @return void
     */
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Set incrementing id as false
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Set primary key as string
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
