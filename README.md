# Console output as a service

[![CI](https://github.com/wazum/console-output-service/actions/workflows/tests.yml/badge.svg)](https://github.com/wazum/console-output-service/actions/workflows/tests.yml)
[![PHP](https://img.shields.io/badge/PHP-8.2%20|%208.3%20|%208.4-blue.svg)](https://www.php.net/)
[![TYPO3](https://img.shields.io/badge/TYPO3-12.4%20|%2013.4-orange.svg)](https://typo3.org/)

## Installation

```
composer require "wazum/console-output-service"
```

## Usage

This little TYPO3 extension provides the Symfony `ConsoleOutput` as a service.

The `ConsoleOutput` can then be injected everywhere it's needed (outside any console command),
so you don't need to pass the instance around (which is impossible, if the service is not invoked directly,
e.g. in an event-based system as in the example below).

```php
namespace Vendor\Extension\EventListener;

use Symfony\Component\Console\Output\ConsoleOutput;

final class OnSomethingHappened
{
    public function __construct(private readonly ConsoleOutput $output)
    {
    }

    public function __invoke(SomethingHappened $event): void
    {
        $this->output->writeln(
                '<info>Something happened!</info>'
        );
    }
}
```

## Background

The extension configures a factory for the `ConsoleOutput`,
which will automatically be used whenever you inject the `ConsoleOutput` as a service.

```yaml
services:
  Wazum\ConsoleOutputService\Console\ConsoleOutputFactory: ~

  Symfony\Component\Console\Output\ConsoleOutput:
    factory: ['@Wazum\ConsoleOutputService\Console\ConsoleOutputFactory', 'create']
```
