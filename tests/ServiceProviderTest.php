<?php

namespace Tests;

use Chronologue\Core\ServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Mockery;

class ServiceProviderTest extends TestCase
{
    protected $app;
    protected ServiceProvider $serviceProvider;

    public function testRegister()
    {
        $this->app->shouldReceive('singleton')->once();
        $this->app->shouldReceive('bind')->times(2);
        $this->app->shouldReceive('resolving')->times(2);

        $this->serviceProvider->register();
        $this->assertTrue(true);
    }

    public function testBoot()
    {
        $this->app->shouldReceive('resolving')->once();

        $this->serviceProvider->boot();
        $this->assertTrue(true);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $app = Mockery::mock(Application::class)->makePartial();

        $config = Mockery::mock(Repository::class)->makePartial();
        $app->instance('config', $config);

        $this->serviceProvider = new ServiceProvider($app);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}