<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Resolver;

use Freeze\Component\DI\Container;
use Freeze\Component\DI\Contract\DefinitionInterface;
use Freeze\Component\DI\Contract\ResolverInterface;
use Freeze\Component\DI\Definition\ArrayDefinition;
use Freeze\Component\DI\Definition\CallableDefinition;
use Freeze\Component\DI\Exception\ServiceNotFoundException;
use Freeze\Component\DI\Exception\UnresolvableServiceException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

final class ConstructorResolver implements ResolverInterface
{
    public function __construct(
        private readonly Container $container
    ) {
    }

    public function resolve(string $identifier): ?DefinitionInterface
    {
        try {
            $reflector = new ReflectionClass($identifier);
        } catch (ReflectionException $e) {
            throw new ServiceNotFoundException("Undefined class {$identifier}", previous: $e);
        }

        $parameters = [];

        $constructor = $reflector->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                if (!($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
                    if ($parameter->isDefaultValueAvailable()) {
                        continue;
                    }

                    return null;
                }

                $parameters[] = new CallableDefinition(
                    $type->getName(),
                    fn(): object => $this->container->get($type->getName())
                );
            }
        }

        return new ArrayDefinition($identifier, $parameters);
    }
}
