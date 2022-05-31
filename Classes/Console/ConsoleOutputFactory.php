<?php

declare(strict_types=1);

namespace Wazum\ConsoleOutputService\Console;

use Symfony\Component\Console\Output\ConsoleOutput;

final class ConsoleOutputFactory
{
    public function create(): ConsoleOutput
    {
        return new ConsoleOutput();
    }
}
