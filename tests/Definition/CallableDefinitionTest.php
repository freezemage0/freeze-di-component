<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Tests\Definition;

use Freeze\Component\DI\Definition\CallableDefinition;
use PHPUnit\Framework\TestCase;
use stdClass;

final class CallableDefinitionTest extends TestCase
{
    public function testInstantiate(): void
    {
        $callableDefinition = new CallableDefinition(
            'identity',
            fn (): object => new stdClass()
        );

        $this->assertInstanceOf(stdClass::class, $callableDefinition->instantiate());
    }
}
