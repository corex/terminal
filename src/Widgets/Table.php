<?php

declare(strict_types=1);

namespace CoRex\Terminal\Widgets;

use CoRex\Helpers\Str;
use CoRex\Terminal\Style;

class Table
{
    /** @var string[] */
    private $headers;

    /** @var mixed[] */
    private $columns;

    /** @var mixed[] */
    private $widths;

    /** @var mixed[] */
    private $rows;

    /** @var string */
    private $charCross = '+';

    /** @var string */
    private $charHorizontal = '-';

    /** @var string */
    private $charVertical = '|';

    /**
     * Table.
     */
    public function __construct()
    {
        $this->headers = [];
        $this->columns = [];
        $this->widths = [];
        $this->rows = [];
    }

    /**
     * Set headers.
     *
     * @param string[] $headers
     */
    public function setHeaders(array $headers): void
    {
        $columnNumber = 0;
        foreach ($headers as $header) {
            $this->updateWidth($columnNumber, $this->length($header));
            if (!in_array($header, $this->headers)) {
                $this->headers[] = $header;
            }
            $columnNumber++;
        }
    }

    /**
     * Set rows.
     *
     * @param mixed[] $rows
     */
    public function setRows(array $rows): void
    {
        foreach ($rows as $row) {
            $columnNumber = 0;
            if (!is_array($row)) {
                $row = [$row];
            }
            foreach ($row as $column => $value) {
                $this->updateWidth($columnNumber, $this->length((string)$column));
                $this->updateWidth($columnNumber, $this->length((string)$value));
                if (!in_array($column, $this->columns)) {
                    $this->columns[] = $column;
                }
                $columnNumber++;
            }
            $this->rows[] = $row;
        }
    }

    /**
     * Render table.
     *
     * @return string
     */
    public function render(): string
    {
        $output = [];

        // Headers.
        if (count($this->headers) > 0) {

            // Top.
            if (count($this->rows) > 0) {
                $output[] = $this->renderLine();
            }

            // Headers.
            if (count($this->columns) > 0) {
                $line = [];
                $line[] = $this->charVertical;
                $columnNumber = 0;
                foreach ($this->columns as $index => $column) {
                    $title = $column;
                    if (isset($this->headers[$index])) {
                        $title = $this->headers[$index];
                    }
                    $line[] = $this->renderCell($columnNumber, (string)$title, ' ', 'info');
                    $line[] = $this->charVertical;
                    $columnNumber++;
                }
                $output[] = implode('', $line);
            }
        }

        // Body.
        if (count($this->rows) > 0) {

            // Middle.
            $output[] = $this->renderLine();

            // Rows.
            foreach ($this->rows as $row) {
                $output[] = $this->renderRow($row);
            }

            // Footer
            $output[] = $this->renderLine();
        }

        return implode("\n", $output);
    }

    /**
     * Render line.
     *
     * @return string
     */
    private function renderLine(): string
    {
        $output = [];
        $output[] = $this->charCross;
        if (count($this->columns) > 0) {
            for ($columnNumber = 0; $columnNumber < count($this->columns); $columnNumber++) {
                $output[] = $this->renderCell($columnNumber, $this->charHorizontal, $this->charHorizontal);
                $output[] = $this->charCross;
            }
        }
        return implode('', $output);
    }

    /**
     * Render row.
     *
     * @param mixed[] $row
     * @return string
     */
    private function renderRow(array $row): string
    {
        $output = [];
        $output[] = $this->charVertical;
        $columnNumber = 0;
        foreach ($row as $column => $value) {
            $output[] = $this->renderCell($columnNumber, $value, ' ');
            $output[] = $this->charVertical;
            $columnNumber++;
        }
        return implode('', $output);
    }

    /**
     * Render cell.
     *
     * @param int $columnNumber
     * @param string $value
     * @param string $filler
     * @param string $style Default ''.
     * @return string
     */
    private function renderCell(int $columnNumber, string $value, string $filler, string $style = ''): string
    {
        $output = [];
        $width = $this->getWidth($columnNumber);
        $output[] = $filler;
        while ($this->length($value) < $width) {
            $value .= $filler;
        }
        $output[] = Style::applyStyle($value, $style);
        $output[] = $filler;
        return implode('', $output);
    }

    /**
     * Get width of column.
     *
     * @param int $columnNumber
     * @return int
     */
    private function getWidth(int $columnNumber): int
    {
        if (isset($this->widths[$columnNumber])) {
            return $this->widths[$columnNumber];
        }
        return 0;
    }

    /**
     * Update width.
     *
     * @param int $columnNumber
     * @param int $width
     */
    private function updateWidth(int $columnNumber, int $width): void
    {
        if ($width > $this->getWidth($columnNumber)) {
            $this->widths[$columnNumber] = $width;
        }
    }

    /**
     * Length.
     *
     * @param string $string
     * @return int
     */
    private function length(string $string): int
    {
        $string = preg_replace("/\033\[[^m]*m/", '', $string);
        return Str::length($string);
    }
}