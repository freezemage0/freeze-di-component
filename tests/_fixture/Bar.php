<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Tests\_fixture;

final class Bar
{
    public function __construct(
        private readonly FooInterface $foo
    ) {
    }

    public function getValue(): string
    {
        return $this->foo->getValue() . '_bar';
    }
}
