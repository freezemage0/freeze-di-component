<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Definition;

use Closure;
use Freeze\Component\DI\Contract\DefinitionInterface;

final class CallableDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly string $identity,
        private readonly Closure $callback
    ) {
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function instantiate(): object
    {
        return ($this->callback)();
    }
}
