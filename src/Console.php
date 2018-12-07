<?php

declare(strict_types=1);

namespace CoRex\Terminal;

use League\CLImate\CLImate;
use League\CLImate\Util\UtilFactory;

class Console
{
    /** @var int */
    private static $lineLength;

    /** @var UtilFactory */
    private static $utilFactory;

    /** @var CLImate */
    private static $climate;

    /**
     * Set length of line.
     *
     * @param int $lineLength
     */
    public static function setLineLength(int $lineLength): void
    {
        self::$lineLength = $lineLength;
    }

    /**
     * Set length of line as terminal width.
     */
    public static function setLineLengthTerminal(): void
    {
        self::$lineLength = self::getTerminalWidth();
    }

    /**
     * Get length of line.
     *
     * @return int
     */
    public static function getLineLength(): int
    {
        return self::$lineLength === null ? 80 : self::$lineLength;
    }

    /**
     * Get terminal width.
     *
     * @return int
     */
    public static function getTerminalWidth(): int
    {
        return self::utilFactory()->width();
    }

    /**
     * Get terminal height.
     *
     * @return int
     */
    public static function getTerminalHeight(): int
    {
        return self::utilFactory()->height();
    }

    /**
     * Write header (title + separator).
     *
     * @param string $title
     */
    public static function header(string $title): void
    {
        $title = str_pad($title, self::getLineLength(), ' ', STR_PAD_RIGHT);
        self::title($title);
        self::separator('=');
    }

    /**
     * Write separator-line.
     *
     * @param string $character Default '-'.
     */
    public static function separator(string $character = '-'): void
    {
        self::climate()->out(str_repeat($character, self::getLineLength()));
    }

    /**
     * Write title messages.
     *
     * @param string|string[] $messages
     */
    public static function title($messages): void
    {
        self::climate()->yellow()->out($messages);
    }

    /**
     * Error.
     *
     * @param string|string[] $messages
     */
    public static function error($messages): void
    {
        self::climate()->error($messages);
    }

    /**
     * Out.
     *
     * @param string|string[] $messages
     */
    public static function out($messages): void
    {
        self::climate()->out($messages);
    }

    /**
     * Info.
     *
     * @param string|string[] $messages
     */
    public static function info($messages): void
    {
        self::climate()->info($messages);
    }

    /**
     * Shout.
     *
     * @param string|string[] $messages
     */
    public static function shout($messages): void
    {
        self::climate()->shout($messages);
    }

    /**
     * Write warning messages.
     *
     * @param string|string[] $messages
     */
    public static function warning($messages): void
    {
        self::climate()->cyan()->out($messages);
    }

    /**
     * Properties.
     *
     * @param string[] $properties
     * @param string $separator Default ':'.
     */
    public static function properties(array $properties, string $separator = ':'): void
    {
        $keys = array_keys($properties);
        $maxLength = max(array_map('strlen', $keys));
        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $key = str_pad($key, $maxLength, ' ', STR_PAD_RIGHT);
                self::climate()->inline($key . ' ');
                if (strlen($separator) > 0) {
                    self::climate()->inline($separator . ' ');
                }
                self::climate()->out($value);
            }
        }
    }

    /**
     * Show table.
     *
     * @param mixed[] $rows
     * @param string[] $headers Default [].
     */
    public static function table(array $rows, array $headers = []): void
    {
        // Ensure it is a valid array.
        if (count($rows) > 0 && !is_array($rows[0])) {
            $rows = array_map(function ($item) {
                return [$item];
            }, $rows);
        }

        // Add headers if specified.
        if (count($headers) > 0) {
            $rows = array_map(function ($item) use ($headers) {
                return array_combine($headers, array_values($item));
            }, $rows);
        }

        self::climate()->table($rows);
    }

    /**
     * Write words.
     *
     * @param string[] $words
     * @param string $separator Default ', '.
     */
    public static function words(array $words, string $separator = ', '): void
    {
        self::out(implode($separator, $words));
    }

    /**
     * Instance of CLImate.
     *
     * @return CLImate
     */
    public static function climate(): CLImate
    {
        if (!is_object(self::$climate)) {
            self::$climate = new CLImate();
        }
        return self::$climate;
    }

    /**
     * Instance of Symfony Terminal.
     *
     * @return UtilFactory
     */
    private static function utilFactory(): UtilFactory
    {
        if (!is_object(self::$utilFactory)) {
            self::$utilFactory = new UtilFactory();
        }
        return self::$utilFactory;
    }
}