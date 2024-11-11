# Loggr

[![codecov](https://codecov.io/gh/askonomm/loggr/graph/badge.svg?token=lQDNAnAWKn)](https://codecov.io/gh/askonomm/loggr)

An extendable logging utility class brought to you by the frustration of every logging class always having its own unique format, 
which makes using log viewing tools difficult. Instead of having its own yet-another-format that no tool supports, Loggr attempts to match 
many already existing formats, allowing you to use whichever you prefer most.

## Requirements

- PHP 8.3+

## Installation

```bash
composer require asko/loggr
```

## Usage

Loggr is very simple to use, and looks like this:

```php
$loggr = new Loggr(new FileSystemDriver('path-to-logs'));
$loggr->info('context');
```

All you have to do is instantiate Loggr with the appropriate driver for your use case and then simply 
log away with any data you want to give it. It takes scalar values, as well as arrays and objects.

### Methods

Loggr supports all of these logging methods:

- `emergency(mixed $context = null)`
- `alert(mixed $context = null)`
- `critical( mixed $context = null)`
- `error(mixed $context = null)`
- `warning(mixed $context = null)`
- `notice(mixed $context = null)`
- `info(mixed $context = null)`
- `debug(mixed $context = null)`

### Setting log format

You can change the logging format by setting the `format` variable in Loggr constructor to a value of `Format` enum, like so:

```php
$loggr = new Loggr(new FileSystemDriver('path-to-logs'), format: Format::JSON);
```

Or, if that gets a bit too long for just one line, you can also do:

```php
$loggr = new Loggr(new FileSystemDriver('path-to-logs'));
$loggr->format = Format::JSON;
```

Loggr supports the following log formats:

- `Format::JSON` - Entries are JSON objects.
- `Format::Laravel` - Entries correspond to the Laravel log format.
- `Format::Symfony` - Entries correspond to the Symfony log format.
- `Format::IntelliJ` - Entries correspond to the IntelliJ log format.

## Extending

You can extend Loggr with your own custom drivers. A driver is a class that implements the `Driver` interface and is 
responsible for making sure that the serialized log entry would end up in the right place.

### Built-in drivers

- [FileSystemDriver](#)
- [StdoutDriver](#)