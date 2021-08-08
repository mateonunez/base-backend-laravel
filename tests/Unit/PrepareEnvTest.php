<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Core\Utils\ModelUtils;
use App\Console\Commands\PrepareEnv;
use Illuminate\Support\Facades\File;

class PrepareEnvTest extends TestCase
{
    /**
     * @group prepare_env_test
     */
    public function test_env_file_copied()
    {
        $envTesting = '.env.testing';
        $envTestingRealpath = base_path() . DIRECTORY_SEPARATOR . $envTesting;

        $envTestingBak = '.env.testing.bak';
        $envTestingRealpathBak = base_path() . DIRECTORY_SEPARATOR . $envTestingBak;

        File::move($envTestingRealpath, $envTestingRealpathBak);

        $prepareEnvCommand = new PrepareEnv();

        $result = $prepareEnvCommand->copyEnvFile(true);

        $this->assertTrue($result);

        File::delete($envTestingRealpath);

        File::move($envTestingRealpathBak, $envTestingRealpath);
    }

    /**
     * @group prepare_env_test
     */
    public function test_env_not_copied()
    {
        $prepareEnvCommand = new PrepareEnv();

        $result = $prepareEnvCommand->copyEnvFile(true);

        $this->assertFalse($result);
    }

    /**
     * @group prepare_env_test
     */
    public function test_application_key_exists()
    {
        $prepareEnvCommand = new PrepareEnv();

        $this->assertTrue($prepareEnvCommand->applicationKeyExists(true));
    }

    /**
     * @group prepare_env_test
     */
    public function test_application_key_generation()
    {
        $env = '.env.testing';
        $envRealpath = base_path() . DIRECTORY_SEPARATOR . $env;

        $envParsed = \Dotenv\Dotenv::parse(file_get_contents($envRealpath));
        $appKey = $envParsed['APP_KEY'];

        $prepareEnvCommand = new PrepareEnv();

        $prepareEnvCommand->generateApplicationKey(true);

        $newEnvParsed = \Dotenv\Dotenv::parse(file_get_contents($envRealpath));
        $newAppKey = $newEnvParsed['APP_KEY'];

        $this->assertNotEquals($newAppKey, $appKey);
    }

    /**
     * @group prepare_env_Test
     */
    public function test_run_migrations()
    {
        \Illuminate\Support\Facades\Artisan::call('migrate --env=testing');

        $prepareEnvCommand = new PrepareEnv();
        $result = $prepareEnvCommand->runMigrations(true);

        $this->assertEquals(
            'Nothing to migrate.',
            $result
        );
    }
}
