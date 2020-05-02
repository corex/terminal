<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal\Writers;

use CoRex\Helpers\Obj;
use CoRex\Terminal\Writers\SymfonyWriter;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\CoRex\Terminal\HelperClasses\TestOutput;

class SymfonyWriterTest extends TestCase
{
    /**
     * Test constructor output.
     *
     * @throws ReflectionException
     */
    public function testConstructor(): void
    {
        $output = new ConsoleOutput();
        $symfonyWriter = new SymfonyWriter($output);
        $this->assertInstanceOf(
            ConsoleOutput::class,
            Obj::getProperty('output', $symfonyWriter)
        );
    }

    /**
     * Test write.
     */
    public function testWrite(): void
    {
        $check = md5((string)mt_rand(1, 100000));
        $output = new TestOutput();
        $symfonyWriter = new SymfonyWriter($output);

        $symfonyWriter->write($check);

        $this->assertEquals($check, $output->getContent());
    }
}