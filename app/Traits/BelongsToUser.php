<?php

namespace App\Traits;

use App\Core\SchemaUtils;

trait BelongsToUser
{
    /**
     * Fills the user column with logged user id
     *
     * @param string $userColumn
     *
     * @return void
     */
    public function fillUser(
        string $userColumn = 'user_id'
    ) {
        if (SchemaUtils::hasAttribute($userColumn, $this)) {
            $this->{$userColumn} = \Illuminate\Support\Facades\Auth::user()->id;
        }
    }
}
