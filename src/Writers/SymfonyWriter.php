<?php

declare(strict_types=1);

namespace CoRex\Terminal\Writers;

use League\CLImate\Util\Writer\WriterInterface;

class SymfonyWriter implements WriterInterface
{
    /** @var object */
    private $output;

    /**
     * SymfonyWriter
     *
     * @param object $output
     */
    public function __construct(object $output)
    {
        $this->output = $output;
    }

    /**
     * Write.
     *
     * @param string $content
     */
    public function write($content): void
    {
        $this->output->write($content);
    }
}