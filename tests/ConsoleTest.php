<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal;

use CoRex\Helpers\Obj;
use CoRex\Terminal\Console;
use League\CLImate\CLImate;
use League\CLImate\Util\UtilFactory;
use PHPUnit\Framework\TestCase;
use Tests\CoRex\Terminal\Helpers\TestWriter;

class ConsoleTest extends TestCase
{
    /** @var TestWriter */
    private $writer;

    /**
     * Test set line length.
     *
     * @throws \ReflectionException
     */
    public function testSetLineLength(): void
    {
        Obj::setProperty('lineLength', null, null, Console::class);
        $this->assertEquals(80, Console::getLineLength());
        Console::setLineLength(120);
        $this->assertEquals(120, Console::getLineLength());
    }

    /**
     * Test set line length terminal
     *
     * @throws \ReflectionException
     */
    public function testSetLineLengthTerminal(): void
    {
        Obj::setProperty('lineLength', null, null, Console::class);
        $this->assertEquals(80, Console::getLineLength());
        Console::setLineLengthTerminal();
        $this->assertEquals(Console::getTerminalWidth(), Console::getLineLength());
    }

    /**
     * Test get terminal width.
     */
    public function testGetTerminalWidth(): void
    {
        $utilFactory = new UtilFactory();
        $this->assertEquals($utilFactory->width(), Console::getTerminalWidth());
    }

    /**
     * Test get terminal height.
     */
    public function testGetTerminalHeight(): void
    {
        $utilFactory = new UtilFactory();
        $this->assertEquals($utilFactory->height(), Console::getTerminalHeight());
    }

    /**
     * Test header.
     */
    public function testHeader(): void
    {
        Console::header('title');
        $content = $this->writer->getContent();
        $content = explode("\n", $content);
        $title = str_pad('title', Console::getLineLength(), ' ', STR_PAD_RIGHT);
        $this->assertEquals($title, $content[0]);
        $this->assertEquals(str_pad('=', Console::getLineLength(), '=', STR_PAD_RIGHT), $content[1]);
        $this->assertEquals('', $content[2]);
    }

    /**
     * Test separator.
     */
    public function testSeparator(): void
    {
        Console::separator();
        $content = $this->writer->getContent();
        $this->assertEquals(str_repeat('-', Console::getLineLength()) . "\n", $content);
    }

    /**
     * Test separator character.
     */
    public function testSeparatorCharacter(): void
    {
        Console::separator('X');
        $content = $this->writer->getContent();
        $this->assertEquals(str_repeat('X', Console::getLineLength()) . "\n", $content);
    }

    /**
     * Test title.
     */
    public function testTitle(): void
    {
        Console::title(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test error.
     */
    public function testError(): void
    {
        Console::error(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test out.
     */
    public function testOut(): void
    {
        Console::out(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test info.
     */
    public function testInfo(): void
    {
        Console::info(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test shout.
     */
    public function testShout(): void
    {
        Console::shout(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test warning.
     */
    public function testWarning(): void
    {
        Console::warning(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test properties.
     */
    public function testProperties(): void
    {
        $data = [
            'test1' => '1',
            'test22' => '22'
        ];
        Console::properties($data);
        $content = $this->writer->getContent();
        $content = explode("\n", $content);
        $this->assertEquals('test1  : 1', $content[0]);
        $this->assertEquals('test22 : 22', $content[1]);
    }

    /**
     * Test table no headers.
     */
    public function testTableNoHeaders(): void
    {
        $items = ['test1', 'test2'];
        Console::table($items);
        $content = $this->writer->getContent();
        $content = explode("\n", $content);
        $this->assertEquals('---------', $content[0]);
        $this->assertEquals('| test1 |', $content[1]);
        $this->assertEquals('---------', $content[2]);
        $this->assertEquals('| test2 |', $content[3]);
        $this->assertEquals('---------', $content[4]);
    }

    /**
     * Test table with headers.
     */
    public function testTableWithHeaders(): void
    {
        $items = ['test1', 'test2'];
        Console::table($items, ['Test']);
        $content = $this->writer->getContent();
        $content = explode("\n", $content);
        $this->assertEquals('---------', $content[0]);
        $this->assertEquals('| Test  |', $content[1]);
        $this->assertEquals('=========', $content[2]);
        $this->assertEquals('| test1 |', $content[3]);
        $this->assertEquals('---------', $content[4]);
        $this->assertEquals('| test2 |', $content[5]);
        $this->assertEquals('---------', $content[6]);
    }

    /**
     * Test words.
     */
    public function testWords(): void
    {
        $words = ['test1', 'test2', 'test3', 'test4'];
        Console::words($words);
        $content = $this->writer->getContent();
        $this->assertEquals(implode(', ', $words) . "\n", $content);
    }

    /**
     * Test climate.
     *
     * @throws \ReflectionException
     */
    public function testClimate(): void
    {
        $instance = Obj::callMethod('climate', null, [], Console::class);
        $this->assertInstanceOf(CLImate::class, $instance);
    }

    /**
     * Test util factory.
     *
     * @throws \ReflectionException
     */
    public function testUtilFactory(): void
    {
        $instance = Obj::callMethod('utilFactory', null, [], Console::class);
        $this->assertInstanceOf(UtilFactory::class, $instance);
    }

    /**
     * Setup.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->writer = new TestWriter();
        $climate = Console::climate();
        $climate->forceAnsiOff(); // It is ok to force ansi off since it is tested elsewhere.
        $climate->output->add('logger', $this->writer);
        $climate->output->defaultTo('logger');
    }
}
