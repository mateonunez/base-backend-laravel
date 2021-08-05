<?php

namespace App\Traits;

use App\Core\SchemaUtils;

trait BelongsToUser
{
    /**
     * Gets the store validation rule using Schema to
     * determinates columns length
     *
     * @return mixed
     */
    public function fillUser(
        string $userColumn = 'user_id'
    ) {
        if (SchemaUtils::hasAttribute($userColumn, $this)) {
            $this->{$userColumn} = \Illuminate\Support\Facades\Auth::user()->id;
        }
    }
}
