<?php

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function withoutExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function render($request, $e)
            {
                throw $e;
            }
        });
    }
}
