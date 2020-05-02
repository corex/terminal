# Terminal

![License](https://img.shields.io/packagist/l/corex/terminal.svg)
![Build Status](https://travis-ci.org/corex/terminal.svg?branch=master)
![codecov](https://codecov.io/gh/corex/terminal/branch/master/graph/badge.svg)


This package is using package league/climate as base.
A few basic static methods has been added for easy access.

It is possible to set Symfony output on Console::class.

### Easy access to CLImate
CLImate can easily be reached through singleton method climate().
Documentation for CLImate can be found on https://climate.thephpleague.com/
```php
$climate = Console::climate();
$climate->...
```


### Static often used methods.
```php
// Get width of terminal.
$width = Console::getTerminalWidth();

// Get height of terminal.
$height = Console::getTerminalHeight();

// Show error message(s).
Console::error($messages);

// Show simple message(s) with no styling.
Console::out($messages);

// Show info message(s).
Console::info($messages);

// Show shout message(s).
Console::shout($messages);

// Show warning message(s) in cyan.
Console::warning($messages);

// Show table with option to set new headers.
Console::table(array $rows, array $headers = []);
```


### Additional static methods.
```php
// Show separator (default length = 80. Can be changed through setLineLength*).
Console::separator($character = '-');

// Show header (title through ::info() followed by ::separator('=')).
Console::header($title);

// Show properties in a table with properties at the left.
Console::properties(array $properties, $separator = ':');

// Show list of words (same as implode($separator, $words)).
Console::words(array $words, $separator = ', ');
```


### Credits
This package is heavily based on the awesome work of developers in team league (The League of Extraordinary Packages).
All credits for the excellent work goes to them.
