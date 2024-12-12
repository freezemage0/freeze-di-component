<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

interface FooInterface {
    public function getValue(): string;
}

class Foo implements FooInterface
{
    public function __construct(private readonly string $value = 'value')
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

class Bar
{
    public function __construct(
        private readonly FooInterface $foo
    ) {
    }

    public function getValue(): string
    {
        return "{$this->foo->getValue()}_bar";
    }
}

$container = new \Freeze\Component\DI\Container();
$container->alias(FooInterface::class, Foo::class);
var_dump($container->get(Bar::class)->getValue());
