<?php

namespace App\Traits;

trait UpdateValidation
{
    /**
     * Gets the update validation rule using Schema to
     * determinates columns length
     *
     * @return array
     */
    public function getUpdateValidationRules(): array
    {
        return [];
    }
}
