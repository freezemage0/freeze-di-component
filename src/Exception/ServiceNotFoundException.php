<?php

declare(strict_types=1);

namespace Freeze\Component\DI\Exception;

use Freeze\Component\DI\Contract\ExceptionInterface;
use RuntimeException;

final class ServiceNotFoundException extends RuntimeException implements ExceptionInterface
{

}