<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Definition;

use Freeze\Component\DI\Contract\DefinitionInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class ArrayDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly string $identity,
        private readonly array $parameters
    ) {
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function instantiate(): object
    {
        $parameters = \array_map(static fn (DefinitionInterface $d): object => $d->instantiate(), $this->parameters);
        try {
            return (new ReflectionClass($this->identity))->newInstance(...$parameters);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Undefined class {$this->identity}", previous: $e);
        }
    }
}
