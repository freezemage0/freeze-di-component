<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Definition;

use Freeze\Component\DI\Contract\DefinitionInterface;

final class SharedDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly DefinitionInterface $definition,
        public bool $shared = true
    ) {
    }

    public function getIdentity(): string
    {
        return $this->definition->getIdentity();
    }

    public function instantiate(): mixed
    {
        return $this->definition->instantiate();
    }
}
