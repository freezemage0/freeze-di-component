<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Contract;

interface ResolverInterface
{
    public function resolve(string $identifier): ?DefinitionInterface;
}
