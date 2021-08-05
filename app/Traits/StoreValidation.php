<?php

namespace App\Traits;

trait StoreValidation
{
    /**
     * Gets the store validation rule using Schema to
     * determinates columns length
     *
     * @return array
     */
    public function getStoreValidationRules(): array
    {
        return [];
    }
}
