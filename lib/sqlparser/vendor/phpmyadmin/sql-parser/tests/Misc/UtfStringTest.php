<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Misc;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\UtfString;
use Throwable;

use function chr;

class UtfStringTest extends TestCase
{
    /**
     * Sample phrase in French.
     */
    public const TEST_PHRASE = 'Les naïfs ægithales hâtifs pondant à Noël où il '
        . 'gèle sont sûrs d\'être déçus en voyant leurs drôles d\'œufs abîmés.';

    /**
     * The length of the sample phrase.
     */
    public const TEST_PHRASE_LEN = 113;

    public function testArrayAccess(): void
    {
        $str = new UtfString(self::TEST_PHRASE);

        // offsetExists
        $this->assertArrayHasKey(self::TEST_PHRASE_LEN - 1, $str);
        $this->assertArrayNotHasKey(-1, $str);
        $this->assertArrayNotHasKey(self::TEST_PHRASE_LEN, $str);

        // offsetGet
        $this->assertEquals('.', $str[self::TEST_PHRASE_LEN - 1]);
        $this->assertNull($str[-1]);
        $this->assertNull($str[self::TEST_PHRASE_LEN]);
    }

    public function testSet(): void
    {
        $this->expectExceptionMessage('Not implemented.');
        $this->expectException(Throwable::class);
        $str = new UtfString('');
        $str[0] = 'a';
    }

    public function testUnset(): void
    {
        $this->expectExceptionMessage('Not implemented.');
        $this->expectException(Throwable::class);
        $str = new UtfString('');
        unset($str[0]);
    }

    public function testGetCharLength(): void
    {
        $this->assertEquals(1, UtfString::getCharLength(chr(0x00))); // 00000000
        $this->assertEquals(1, UtfString::getCharLength(chr(0x7F))); // 01111111

        $this->assertEquals(2, UtfString::getCharLength(chr(0xC0))); // 11000000
        $this->assertEquals(2, UtfString::getCharLength(chr(0xDF))); // 11011111

        $this->assertEquals(3, UtfString::getCharLength(chr(0xE0))); // 11100000
        $this->assertEquals(3, UtfString::getCharLength(chr(0xEF))); // 11101111

        $this->assertEquals(4, UtfString::getCharLength(chr(0xF0))); // 11110000
        $this->assertEquals(4, UtfString::getCharLength(chr(0xF7))); // 11110111

        $this->assertEquals(5, UtfString::getCharLength(chr(0xF8))); // 11111000
        $this->assertEquals(5, UtfString::getCharLength(chr(0xFB))); // 11111011

        $this->assertEquals(6, UtfString::getCharLength(chr(0xFC))); // 11111100
        $this->assertEquals(6, UtfString::getCharLength(chr(0xFD))); // 11111101
    }

    public function testToString(): void
    {
        $str = new UtfString(self::TEST_PHRASE);
        $this->assertEquals(self::TEST_PHRASE, (string) $str);
    }

    /**
     * Test access to string.
     *
     * @dataProvider utf8StringsProvider
     */
    public function testAccess(string $text, ?string $pos10, ?string $pos20): void
    {
        $str = new UtfString($text);
        $this->assertEquals($pos10, $str->offsetGet(10));
        $this->assertEquals($pos20, $str->offsetGet(20));
        $this->assertEquals($pos10, $str->offsetGet(10));
    }

    /**
     * @return array<string, array<int, string|null>>
     * @psalm-return array<string, array{string, (string|null), (string|null)}>
     */
    public function utf8StringsProvider(): array
    {
        return [
            'ascii' => [
                'abcdefghijklmnopqrstuvwxyz',
                'k',
                'u',
            ],
            'unicode' => [
                'áéíóúýěřťǔǐǒǎšďȟǰǩľžčǚň',
                'ǐ',
                'č',
            ],
            'emoji' => [
                '😂😄😃😀😊😉😍😘😚😗😂👿😮😨😱😠😡😤😖😆😋👯',
                '😂',
                '😋',
            ],
            'iso' => [
                "P\xf8\xed\xb9ern\xec \xbelu\xbbou\xe8k\xfd k\xf3d \xfap\xecl \xef\xe1belsk\xe9 k\xf3dy",
                null,
                null,
            ],
        ];
    }
}
