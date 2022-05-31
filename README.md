# Console output as a service

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
