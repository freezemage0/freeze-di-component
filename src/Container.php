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
     * @param class-string<T> $identifier
     *
     * @return T
     */
    public function get(string $identifier): object
    {
        if (!isset($this->definitions[$identifier])) {
            $this->define($this->resolve($identifier));
        }

        if (!isset($this->instances[$identifier])) {
            $definition = $this->definitions[$identifier];
            $instance = $definition->instantiate();

            if (!($definition instanceof SharedDefinition) || $definition->shared) {
                $this->instances[$identifier] = $instance;
            }
        }

        return $this->instances[$identifier];
    }

    public function has(string $identifier): bool
    {
        return isset($this->definitions[$identifier]) || isset($this->instances[$identifier]);
    }

    public function define(DefinitionInterface $definition): void
    {
        $this->definitions[$definition->getIdentity()] = $definition;
    }

    public function share(string $identity, bool $share = true): void
    {
        if (!isset($this->definitions[$identity])) {
            throw new ServiceNotFoundException("Unknown service {$identity}");
        }

        $definition = $this->definitions[$identity];
        if (!($definition instanceof SharedDefinition)) {
            $definition = new SharedDefinition($definition, $share);
        } else {
            $definition->shared = $share;
        }
        $this->definitions[$identity] = $definition;
    }

    public function alias(string $identity, string $alias): void
    {
        $this->define(new ReferenceDefinition($this, $identity, $alias));
    }

    public function factory(string $identity, callable $factory): void
    {
        $this->define(new CallableDefinition($identity, $factory));
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
