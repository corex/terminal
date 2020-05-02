<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal\Helpers;

use League\CLImate\Util\Writer\WriterInterface;

class TestWriter implements WriterInterface
{
    /** @var string */
    private $content;

    /**
     * Get content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Write.
     *
     * @param mixed $content
     * @return void
     */
    public function write($content): void
    {
        if ($this->content === null) {
            $this->content = '';
        }

        $this->content .= $content;
    }
}