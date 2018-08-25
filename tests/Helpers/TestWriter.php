<?php

namespace Tests\CoRex\Terminal\Helpers;

use League\CLImate\Util\Writer\WriterInterface;

class TestWriter implements WriterInterface
{
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
     * @param  string $content
     *
     * @return void
     */
    public function write($content)
    {
        if ($this->content === null) {
            $this->content = '';
        }
        $this->content .= $content;
    }
}