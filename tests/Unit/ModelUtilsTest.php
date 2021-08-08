<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Core\Utils\ModelUtils;

class ModelUtilsTest extends TestCase
{
    /**
     * @group model_utils
     */
    public function test_user_use_uuid_trait()
    {
        // Asserting that User class use Uuid trais
        $this->assertTrue(ModelUtils::usesTrait(User::class, \App\Traits\Uuid::class));
    }

    /**
     * @group model_utils
     */
    public function test_user_dont_use_store_validation_trait()
    {
        // Asserting that User does not use StoreValidation trait
        $this->assertFalse(ModelUtils::usesTrait(User::class, \App\Traits\StoreValidation::class));
    }

    /**
     * @group model_utils
     */
    public function test_user_has_no_relations()
    {
        $relations = ModelUtils::relations(User::class);

        $this->assertEmpty($relations);
    }
}
