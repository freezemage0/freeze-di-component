<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Definition;

use Freeze\Component\DI\Container;
use Freeze\Component\DI\Contract\DefinitionInterface;

final class ReferenceDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly Container $container,
        public readonly string $identity,
        public readonly string $alias
    ) {
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function instantiate(): object
    {
        return $this->container->get($this->alias);
    }
}
