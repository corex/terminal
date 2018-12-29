<?php

declare(strict_types=1);

namespace Tests\CoRex\Terminal\Helpers;

use CoRex\Terminal\Helpers\OutputFormatterStyle;
use CoRex\Terminal\Style;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{
    /**
     * Test setStyle.
     */
    public function testSetStyle(): void
    {
        $this->assertSame([], Style::getStyle('unknown'));
        Style::setStyle('unknown', 'test1', 'test2');
        $this->assertSame(['foreground' => 'test1', 'background' => 'test2'], Style::getStyle('unknown'));
        Style::forgetStyle('unknown');
        $this->assertSame([], Style::getStyle('unknown'));
    }

    /**
     * Test getStyle.
     */
    public function testGetStyle(): void
    {
        $this->assertSame(['foreground' => 'white', 'background' => 'red'], Style::getStyle('error'));
    }

    /**
     * Test getStyle unknown.
     */
    public function testGetStyleUnknown(): void
    {
        $this->assertSame([], Style::getStyle('unknown'));
    }

    /**
     * Test getForeground.
     */
    public function testGetForeground(): void
    {
        $this->assertSame('', Style::getForeground('unknown'));
        Style::setStyle('unknown', 'test1', 'test2');
        $this->assertSame('test1', Style::getForeground('unknown'));
        Style::forgetStyle('unknown');
        $this->assertSame('', Style::getForeground('unknown'));
    }

    /**
     * Test getBackground.
     */
    public function testGetBackground(): void
    {
        $this->assertSame('', Style::getBackground('unknown'));
        Style::setStyle('unknown', 'test1', 'test2');
        $this->assertSame('test2', Style::getBackground('unknown'));
        Style::forgetStyle('unknown');
        $this->assertSame('', Style::getBackground('unknown'));
    }

    /**
     * Test apply.
     *
     * @throws \Exception
     */
    public function testApply(): void
    {
        $style = new OutputFormatterStyle();
        $style->setForeground('white');
        $style->setBackground('red');
        $styledText = $style->apply('test');
        $this->assertSame($styledText, Style::apply('test', 'white', 'red'));
    }

    /**
     * Test apply.
     *
     * @throws \Exception
     */
    public function testApplyException(): void
    {
        $text = Style::apply('test', 'unknown', 'unknown');
        $this->assertSame('test', $text);
    }

    /**
     * Test applyStyle.
     *
     * @throws \Exception
     */
    public function testApplyStyle(): void
    {
        $style = new OutputFormatterStyle();
        $style->setForeground('white');
        $style->setBackground('red');
        $styledText = $style->apply('test');
        $this->assertSame($styledText, Style::applyStyle('test', 'error'));
    }
}