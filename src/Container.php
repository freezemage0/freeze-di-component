<?php

declare(strict_types=1);

namespace Freeze\Component\DI;

use Freeze\Component\DI\Contract\ContainerInterface;
use Freeze\Component\DI\Contract\DefinitionInterface;
use Freeze\Component\DI\Contract\ResolverInterface;
use Freeze\Component\DI\Definition\CallableDefinition;
use Freeze\Component\DI\Definition\ReferenceDefinition;
use Freeze\Component\DI\Definition\SharedDefinition;
use Freeze\Component\DI\Exception\ServiceNotFoundException;
use Freeze\Component\DI\Exception\UnresolvableServiceException;
use Freeze\Component\DI\Resolver\ConstructorResolver;

final class Container implements ContainerInterface
{
    /** @var array<string, DefinitionInterface> */
    private array $definitions = [];
    private array $instances = [];
    private array $resolvers;

    public function __construct(ResolverInterface ...$resolvers)
    {
        if (empty($resolvers)) {
            $resolvers = [new ConstructorResolver($this)];
        }

        $this->resolvers = $resolvers;
    }

    /**
     * @template T
     * @param class-string<T> $id
     *
     * @return T
     */
    public function get(string $id): object
    {
        if (!isset($this->definitions[$id])) {
            $this->define($this->resolve($id));
        }

        if (!isset($this->instances[$id])) {
            $definition = $this->definitions[$id];
            $instance = $definition->instantiate();

            if (!($definition instanceof SharedDefinition) || $definition->shared) {
                $this->instances[$id] = $instance;
            }
        }

        return $this->instances[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]) || isset($this->instances[$id]);
    }

    public function define(DefinitionInterface $definition): void
    {
        $this->definitions[$definition->getIdentity()] = $definition;
    }

    public function share(string $id, bool $share = true): void
    {
        if (!isset($this->definitions[$id])) {
            throw new ServiceNotFoundException("Unknown service {$id}");
        }

        $definition = $this->definitions[$id];
        if (!($definition instanceof SharedDefinition)) {
            $definition = new SharedDefinition($definition, $share);
        } else {
            $definition->shared = $share;
        }
        $this->definitions[$id] = $definition;
    }

    public function alias(string $id, string $alias): void
    {
        $this->define(new ReferenceDefinition($this, $id, $alias));
    }

    public function factory(string $id, callable $factory): void
    {
        $this->define(new CallableDefinition($id, $factory));
    }

    private function resolve(string $identifier): DefinitionInterface
    {
        foreach ($this->resolvers as $resolver) {
            $definition = $resolver->resolve($identifier);
            if ($definition !== null) {
                return $definition;
            }
        }

        throw new UnresolvableServiceException("Unresolvable service {$identifier}");
    }
}
