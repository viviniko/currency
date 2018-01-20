<?php

namespace Viviniko\Currency\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class CurrencyTableCommand extends CreateMigrationCommand
{
    protected $name = 'currency:table';

    protected $description = 'Create a migration for the currency service table';

    protected $stub = __DIR__ . '/stubs/currency.stub';

    protected $migration = 'create_currency_table';
}