<?php

declare(strict_types=1);

namespace CoRex\Terminal;

use CoRex\Helpers\Arr;
use CoRex\Terminal\Helpers\OutputFormatterStyle;

class Style
{
    /** @var mixed[] */
    private static $styles = [
        'normal' => ['foreground' => 'white', 'background' => ''],
        'error' => ['foreground' => 'white', 'background' => 'red'],
        'warning' => ['foreground' => 'cyan', 'background' => ''],
        'info' => ['foreground' => 'green', 'background' => ''],
        'comment' => ['foreground' => 'green', 'background' => ''],
        'title' => ['foreground' => 'yellow', 'background' => '']
    ];

    /**
     * Get styles.
     *
     * @return array
     */
    public static function getStyles(): array
    {
        return self::$styles;
    }

    /**
     * Set style.
     *
     * @param string $style
     * @param string $foreground
     * @param string $background
     */
    public static function setStyle(string $style, string $foreground, string $background): void
    {
        self::$styles[$style] = [
            'foreground' => $foreground,
            'background' => $background
        ];
    }

    /**
     * Get style.
     *
     * @param string $style
     * @return mixed[]
     */
    public static function getStyle(string $style): array
    {
        if (isset(self::$styles[$style])) {
            return self::$styles[$style];
        }
        return [];
    }

    /**
     * Forget style.
     *
     * @param string $style
     */
    public static function forgetStyle(string $style): void
    {
        if (isset(self::$styles[$style])) {
            unset(self::$styles[$style]);
        }
    }

    /**
     * Get foreground color.
     *
     * @param string $style
     * @return string
     */
    public static function getForeground(string $style): string
    {
        return self::get($style, 'foreground');
    }

    /**
     * Get background color.
     *
     * @param string $style
     * @return string
     */
    public static function getBackground(string $style): string
    {
        return self::get($style, 'background');
    }

    /**
     * Apply foreground and background color.
     *
     * @param string $text
     * @param string $foreground Default ''.
     * @param string $background Default ''.
     * @return string
     */
    public static function apply(string $text, string $foreground = '', string $background = ''): string
    {
        try {
            $style = new OutputFormatterStyle();
            if ($foreground !== '') {
                $style->setForeground($foreground);
            }
            if ($background !== '') {
                $style->setBackground($background);
            }
            return $style->apply($text);
        } catch (\Exception $e) {
            return $text;
        }
    }

    /**
     * Apply style.
     *
     * @param string $text
     * @param string $style
     * @return string
     */
    public static function applyStyle(string $text, string $style): string
    {
        $foreground = self::getForeground($style);
        $background = self::getBackground($style);
        return self::apply($text, $foreground, $background);
    }

    /**
     * Get style setting.
     *
     * @param string $style
     * @param string $setting
     * @param string $defaultValue Default ''.
     * @return string
     */
    private static function get(string $style, string $setting, string $defaultValue = ''): string
    {
        if (isset(self::$styles[$style])) {
            $style = self::$styles[$style];
            if (isset($style[$setting])) {
                return $style[$setting];
            }
        }
        return $defaultValue;
    }
}