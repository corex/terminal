<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal\HelperClasses;

use Symfony\Component\Console\Output\Output;

class TestOutput extends Output
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
     * Do write.
     *
     * @param string $message
     * @param bool $newline
     */
    protected function doWrite(string $message, bool $newline)
    {
        if ($this->content === null) {
            $this->content = '';
        }

        $this->content .= $message;

        if ($newline) {
            $this->content .= PHP_EOL;
        }
    }
}