<?php

namespace Thotam\ThotamHr\Tests;

use Orchestra\Testbench\TestCase;
use Thotam\ThotamHr\ThotamHrServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ThotamHrServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
