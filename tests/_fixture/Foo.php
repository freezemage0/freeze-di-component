<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Tests\_fixture;

final class Foo implements FooInterface
{
    public function __construct(
        private readonly string $value
    )
    {

    }

    public function getValue(): string
    {
        return $this->value;
    }
}
