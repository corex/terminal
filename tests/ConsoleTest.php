<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal;

use CoRex\Helpers\Obj;
use CoRex\Helpers\Str;
use CoRex\Terminal\Console;
use CoRex\Terminal\Style;
use Exception;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Checkbox\CheckboxGroup;
use League\CLImate\TerminalObject\Dynamic\Checkboxes;
use League\CLImate\TerminalObject\Dynamic\Confirm;
use League\CLImate\TerminalObject\Dynamic\Input;
use League\CLImate\TerminalObject\Dynamic\InputAbstract;
use League\CLImate\TerminalObject\Dynamic\Password;
use League\CLImate\Util\UtilFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\CoRex\Terminal\Helpers\TestWriter;

class ConsoleTest extends TestCase
{
    /** @var TestWriter */
    private $writer;

    /**
     * Test set Symfony output.
     *
     * @throws ReflectionException
     */
    public function testSetSymfonyOutput(): void
    {
        // Validate not set.
        $availableWriters = array_keys(Console::climate()->output->getAvailable());
        $this->assertFalse(in_array('custom', $availableWriters));

        // Reset console object for CLImate.
        Obj::setProperty('climate', null, null, Console::class);

        // Set Symfony console output.
        $output = new ConsoleOutput();
        Console::setSymfonyOutput($output);

        // Validate custom writer set.
        $climate = Console::climate();
        $availableWriters = array_keys($climate->output->getAvailable());
        $this->assertTrue(in_array('custom', $availableWriters));

        // Validate Symfony console output set.
        $customWriter = $climate->output->get('custom');
        $output = Obj::getProperty('output', $customWriter);
        $this->assertEquals(get_class($output), ConsoleOutput::class);
    }

    /**
     * Test set Symfony output invalid output.
     *
     * @throws Exception
     */
    public function testSetSymfonyOutputInvalidOutput(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid Symfony Output.');
        Console::setSymfonyOutput(new TestWriter());
    }

    /**
     * Test set line length.
     *
     * @throws ReflectionException
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
     * @throws ReflectionException
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
     * Test write.
     */
    public function testWrite(): void
    {
        Console::write(__FUNCTION__);
        $this->assertEquals(__FUNCTION__, $this->writer->getContent());
    }

    /**
     * Test writeln.
     */
    public function testWriteln(): void
    {
        Console::writeln(__FUNCTION__);
        $this->assertEquals(__FUNCTION__ . "\n", $this->writer->getContent());
    }

    /**
     * Test br.
     */
    public function testBR(): void
    {
        Console::br(7);
        $this->assertEquals(str_repeat("\n", 7), $this->writer->getContent());
    }

    /**
     * Test clear.
     */
    public function testClear(): void
    {
        Console::clear();
        $this->assertTrue(Str::contains($this->writer->getContent(), "\e[H\e[2J"));
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
        $this->assertEquals('+-------+', $content[0]);
        $this->assertEquals('| test1 |', $content[1]);
        $this->assertEquals('| test2 |', $content[2]);
        $this->assertEquals('+-------+', $content[3]);
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
        $this->assertEquals('+-------+', $content[0]);
        $this->assertEquals('| ' . Style::applyStyle('Test ', 'info') . ' |', $content[1]);
        $this->assertEquals('+-------+', $content[2]);
        $this->assertEquals('| test1 |', $content[3]);
        $this->assertEquals('| test2 |', $content[4]);
        $this->assertEquals('+-------+', $content[5]);
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
     * Test ask.
     *
     * @throws ReflectionException
     */
    public function testAsk(): void
    {
        $check = md5((string)mt_rand(1, 100000));
        $input = Console::ask($check . '1', $check . '2', false);
        $this->assertInstanceOf(Input::class, $input);
        $this->assertSame($check . '1', Obj::getProperty('prompt', $input, null, InputAbstract::class));
        $this->assertSame($check . '2', Obj::getProperty('default', $input, null, Input::class));
    }

    /**
     * Test secret.
     *
     * @throws ReflectionException
     */
    public function testPassword(): void
    {
        $check = md5((string)mt_rand(1, 100000));
        $input = Console::password($check, false);
        $this->assertInstanceOf(Password::class, $input);
        $this->assertSame($check, Obj::getProperty('prompt', $input, null, InputAbstract::class));
    }

    /**
     * Test confirm.
     *
     * @throws ReflectionException
     */
    public function testConfirm(): void
    {
        $check = md5((string)mt_rand(1, 100000));
        $input = Console::confirm($check, 'y', false);
        $this->assertInstanceOf(Confirm::class, $input);
        $this->assertSame($check, Obj::getProperty('prompt', $input, null, InputAbstract::class));
    }

    /**
     * Test checkboxes.
     *
     * @throws ReflectionException
     */
    public function testCheckboxes(): void
    {
        $options = ['test1', 'test2'];
        $check = md5((string)mt_rand(1, 100000));
        $input = Console::checkboxes($check, $options, false);
        $this->assertInstanceOf(Checkboxes::class, $input);
        $this->assertSame($check, Obj::getProperty('prompt', $input, null, InputAbstract::class));

        $checkboxes = Obj::getProperty('checkboxes', $input, null, Checkboxes::class);
        $this->assertEquals(2, Obj::getProperty('count', $checkboxes, null, CheckboxGroup::class));
    }

    /**
     * Test radio.
     *
     * @throws ReflectionException
     */
    public function testRadio(): void
    {
        $options = ['test1', 'test2'];
        $check = md5((string)mt_rand(1, 100000));
        $input = Console::radio($check, $options, false);
        $this->assertInstanceOf(Checkboxes::class, $input);
        $this->assertSame($check, Obj::getProperty('prompt', $input, null, InputAbstract::class));

        $checkboxes = Obj::getProperty('checkboxes', $input, null, Checkboxes::class);
        $this->assertEquals(2, Obj::getProperty('count', $checkboxes, null, CheckboxGroup::class));
    }

    /**
     * Test climate.
     *
     * @throws ReflectionException
     */
    public function testClimate(): void
    {
        $instance = Obj::callMethod('climate', null, [], Console::class);
        $this->assertInstanceOf(CLImate::class, $instance);
    }

    /**
     * Test util factory.
     *
     * @throws ReflectionException
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
        $climate->output->add('tester', $this->writer);
        $climate->output->defaultTo('tester');
    }
}
