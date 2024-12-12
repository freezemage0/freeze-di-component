<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Contract;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @template T
     * @param class-string<T> $id
     *
     * @return T
     */
    public function get(string $id): object;

    public function has(string $id): bool;

    public function define(DefinitionInterface $definition): void;

    public function share(string $id, bool $share = true): void;

    public function alias(string $id, string $alias): void;

    public function factory(string $id, callable $factory): void;
}
