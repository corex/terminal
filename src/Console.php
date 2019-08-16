<?php

declare(strict_types=1);

namespace CoRex\Terminal;

use CoRex\Terminal\Widgets\Table;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Checkboxes;
use League\CLImate\TerminalObject\Dynamic\Confirm;
use League\CLImate\TerminalObject\Dynamic\Input;
use League\CLImate\TerminalObject\Dynamic\Password;
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
     * Clear.
     */
    public static function clear(): void
    {
        self::climate()->clear();
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
     * Write.
     *
     * @param string $message
     */
    public static function write(string $message): void
    {
        self::climate()->inline($message);
    }

    /**
     * Writeln.
     *
     * @param string $message
     */
    public static function writeln(string $message): void
    {
        self::climate()->out($message);
    }

    /**
     * Linebreak.
     *
     * @param int $count Default 1.
     */
    public static function br(int $count = 1): void
    {
        self::climate()->br($count);
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
        $table = new Table();
        $table->setRows($rows);
        if (count($headers) > 0) {
            $table->setHeaders($headers);
        }
        $output = $table->render();
        self::out($output);
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
     * Ask question.
     *
     * @param string $question
     * @param mixed $defaultValue Default null.
     * @param bool $prompt Default true.
     * @return string|Input
     */
    public static function ask(string $question, ?string $defaultValue = null, bool $prompt = true)
    {
        $input = self::climate()->input($question);
        if ($defaultValue !== null) {
            $input->defaultTo($defaultValue);
        }
        if ($prompt) {
            // @codeCoverageIgnoreStart
            return $input->prompt();
            // @codeCoverageIgnoreEnd
        }
        return $input;
    }

    /**
     * Ask for password.
     *
     * @param string $question
     * @param bool $prompt Default true.
     * @return string|Password
     */
    public static function password(string $question, bool $prompt = true)
    {
        $input = self::climate()->password($question);
        if ($prompt) {
            // @codeCoverageIgnoreStart
            return $input->prompt();
            // @codeCoverageIgnoreEnd
        }
        return $input;
    }

    /**
     * Confirm question.
     *
     * @param string $question
     * @param string|null $defaultValue 'y' or 'n'. Default null which means not set.
     * @param bool $prompt Default true.
     * @return bool|Confirm
     */
    public static function confirm(string $question, ?string $defaultValue = null, bool $prompt = true)
    {
        $input = self::climate()->confirm($question);
        if ($defaultValue !== null) {
            $input->defaultTo($defaultValue);
        }
        if ($prompt) {
            // @codeCoverageIgnoreStart
            return $input->confirmed();
            // @codeCoverageIgnoreEnd
        }
        return $input;
    }

    /**
     * Checkboxes.
     * (Does only works in non-Windows environments.)
     *
     * @param string $question
     * @param mixed[] $options
     * @param bool $prompt
     * @return string|Checkboxes
     */
    public static function checkboxes(string $question, array $options, bool $prompt = true)
    {
        $input = self::climate()->checkboxes($question, $options);
        if ($prompt) {
            // @codeCoverageIgnoreStart
            return $input->prompt();
            // @codeCoverageIgnoreEnd
        }
        return $input;
    }

    /**
     * Radio.
     * .
     * (Does only works in non-Windows environments.)
     *
     * @param string $question
     * @param mixed[] $options
     * @param bool $prompt
     * @return string|Checkboxes
     */
    public static function radio(string $question, array $options, bool $prompt = true)
    {
        $input = self::climate()->radio($question, $options);
        if ($prompt) {
            // @codeCoverageIgnoreStart
            return $input->prompt();
            // @codeCoverageIgnoreEnd
        }
        return $input;
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

            // Add colors from local style class.
            $climateColors = self::$climate->style->all();
            $climateColorNames = array_keys($climateColors);
            $styles = Style::getStyles();
            foreach ($styles as $name => $value) {
                $color = $value['foreground'];
                if (in_array($name, $climateColorNames) && !array_key_exists($color, $climateColorNames)) {
                    continue;
                }
                $colorValue = $climateColors[$color];
                self::$climate->style->addColor($name, $colorValue);
            }
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