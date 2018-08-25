<?php

namespace CoRex\Terminal;

use League\CLImate\CLImate;
use League\CLImate\Util\UtilFactory;

class Console
{
    private static $lineLength;
    private static $utilFactory;
    private static $climate;

    /**
     * Set length of line.
     *
     * @param integer $lineLength
     */
    public static function setLineLength($lineLength)
    {
        self::$lineLength = $lineLength;
    }

    /**
     * Set length of line as terminal width.
     */
    public static function setLineLengthTerminal()
    {
        self::$lineLength = self::getTerminalWidth();
    }

    /**
     * Get length of line.
     *
     * @return integer
     */
    public static function getLineLength()
    {
        return self::$lineLength === null ? 80 : self::$lineLength;
    }

    /**
     * Get terminal width.
     *
     * @return integer
     */
    public static function getTerminalWidth()
    {
        return self::utilFactory()->width();
    }

    /**
     * Get terminal height.
     *
     * @return integer
     */
    public static function getTerminalHeight()
    {
        return self::utilFactory()->height();
    }

    /**
     * Write header (title + separator).
     *
     * @param string $title
     */
    public static function header($title)
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
    public static function separator($character = '-')
    {
        self::climate()->out(str_repeat($character, self::getLineLength()));
    }

    /**
     * Write title messages.
     *
     * @param string|array $messages
     */
    public static function title($messages)
    {
        self::climate()->yellow()->out($messages);
    }

    /**
     * Error.
     *
     * @param string|array $messages
     */
    public static function error($messages)
    {
        self::climate()->error($messages);
    }

    /**
     * Out.
     *
     * @param string|array $messages
     */
    public static function out($messages)
    {
        self::climate()->out($messages);
    }

    /**
     * Info.
     *
     * @param string|array $messages
     */
    public static function info($messages)
    {
        self::climate()->info($messages);
    }

    /**
     * Shout.
     *
     * @param string|array $messages
     */
    public static function shout($messages)
    {
        self::climate()->shout($messages);
    }

    /**
     * Write warning messages.
     *
     * @param string|array $messages
     */
    public static function warning($messages)
    {
        self::climate()->cyan()->out($messages);
    }

    /**
     * Properties.
     *
     * @param array $properties
     * @param string $separator Default ':'.
     */
    public static function properties(array $properties, $separator = ':')
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
     * @param array $rows
     * @param array $headers Default [].
     */
    public static function table(array $rows, array $headers = [])
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
     * @param array $words
     * @param string $separator Default ', '.
     */
    public static function words(array $words, $separator = ', ')
    {
        self::out(implode($separator, $words));
    }

    /**
     * Instance of CLImate.
     *
     * @return CLImate
     */
    public static function climate()
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
    private static function utilFactory()
    {
        if (!is_object(self::$utilFactory)) {
            self::$utilFactory = new UtilFactory();
        }
        return self::$utilFactory;
    }
}