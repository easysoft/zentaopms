<?php

namespace PHPThumb\Tests;

use PHPThumb\GD;

class GDTest extends \PHPUnit_Framework_TestCase
{
    protected $gif;
    protected $jpg;
    protected $png;

    protected function setUp()
    {
        $this->gif = new GD(__DIR__ . '/../resources/test.gif');
        $this->jpg = new GD(__DIR__ . '/../resources/test.jpg');
        $this->png = new GD(__DIR__ . '/../resources/test.png');
    }

    public function testLoadFileTypes()
    {
        self::assertSame('GIF', $this->gif->getFormat());
        self::assertSame('JPG', $this->jpg->getFormat());
        self::assertSame('PNG', $this->png->getFormat());
    }

    /**
     * This test might seem pointless but it runs the __destruct and gets us to
     * 100% code coverage.
     */
    public function testImageDestroy()
    {
        $testImage = new GD(__DIR__ . '/../resources/test.gif');
        unset($testImage);
        self::assertSame(false, isset($testImage));
    }
}
