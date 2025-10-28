<?php

namespace PHPThumb\Tests;

use PHPThumb\GD;

class LoadTest extends \PHPUnit_Framework_TestCase
{
    protected $thumb;

    protected function setUp()
    {
        $this->thumb = new GD(__DIR__ . '/../resources/test.jpg');
    }

    public function testLoadFile()
    {
        self::assertSame(array('width' => 500, 'height' => 375), $this->thumb->getCurrentDimensions());
        self::assertSame(array(
            'resizeUp'              => false,
            'jpegQuality'           => 100,
            'correctPermissions'    => false,
            'preserveAlpha'         => true,
            'alphaMaskColor'        => array (
                                        0 => 255,
                                        1 => 255,
                                        2 => 255),
            'preserveTransparency'  => true,
            'transparencyMaskColor' => array (
                                        0 => 0,
                                        1 => 0,
                                        2 => 0),
            'interlace'             => null), $this->thumb->getOptions());

        self::assertSame('JPG', $this->thumb->getFormat());
        self::assertSame(__DIR__ . '/../resources/test.jpg', $this->thumb->getFileName());
    }

    public function testSetFormat()
    {
        $this->thumb->setFormat('PNG');
        self::assertSame('PNG', $this->thumb->getFormat());
    }

    public function testSetFileName()
    {
        $this->thumb->setFilename('mytest.jpg');
        self::assertSame('mytest.jpg', $this->thumb->getFilename());
    }

    public function testLoadExternalImage()
    {
        /* $gravatarThumb = new GD('https://en.gravatar.com/userimage/1132703/2ccbcfbea4a1b3b8d955c1e7746b882b.jpg');
        self::assertSame(true, $gravatarThumb->getIsRemoteImage()); */
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonexistentFile()
    {
        $madeupThumb = new GD('nosuchimage.jpg');
    }
}
