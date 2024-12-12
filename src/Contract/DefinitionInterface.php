<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Contract;

interface DefinitionInterface
{
    public function getIdentity(): string;

    public function instantiate(): mixed;
}
