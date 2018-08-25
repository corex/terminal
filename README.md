# Terminal

This package is extending package league/climate with some basic static methods for easy access.

All credits for the excellent work in package league/climate goes to team League.

Documentation for CLImate can be found on https://climate.thephpleague.com/

### Easy access to CLImate
CLImate is the base and can easily be reached through method climate(). Uses Singleton.
```php
$climate = Console::climate();
$climate->...
```


### Static methods for easy access.
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
