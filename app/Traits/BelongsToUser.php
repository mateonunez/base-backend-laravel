<?php

namespace App\Traits;

use App\Core\Utils\SchemaUtils;

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
        if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), $userColumn)) {
            $this->{$userColumn} = \Illuminate\Support\Facades\Auth::user()->id;
        }
    }
}
