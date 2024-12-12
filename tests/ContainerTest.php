<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Tests;

use Freeze\Component\DI\Container;
use Freeze\Component\DI\Definition\CallableDefinition;
use Freeze\Component\DI\Tests\_fixture\Bar;
use Freeze\Component\DI\Tests\_fixture\Foo;
use Freeze\Component\DI\Tests\_fixture\FooInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ContainerTest extends TestCase
{
    public function testGet(): void
    {
        $container = new Container();
        $container->define(new CallableDefinition('identity', fn () => new stdClass()));

        $this->assertInstanceOf(stdClass::class, $container->get('identity'));
    }

    public function testHas(): void
    {
        $container = new Container();

        $this->assertFalse($container->has('identity'));
        $container->alias('identity', stdClass::class);
        $this->assertTrue($container->has('identity'));
    }

    public function testGetConstructorResolve(): void
    {
        $container = new Container();
        $container->factory(FooInterface::class, fn () => new Foo('foo'));

        $bar = $container->get(Bar::class);

        $this->assertSame('foo_bar', $bar->getValue());
    }
}
