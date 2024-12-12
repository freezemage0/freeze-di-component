<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Contract;

interface ContainerInterface
{
    /**
     * @template T
     * @param class-string<T> $identifier
     *
     * @return T
     */
    public function get(string $identifier): object;

    public function has(string $identifier): bool;

    public function define(DefinitionInterface $definition): void;

    public function share(string $identity, bool $share = true): void;

    public function alias(string $identity, string $alias): void;

    public function factory(string $identity, callable $factory): void;
}
