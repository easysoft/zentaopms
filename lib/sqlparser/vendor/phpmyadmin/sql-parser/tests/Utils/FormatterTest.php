<?php

namespace PhpMyAdmin\SqlParser\Tests\Utils;

use PhpMyAdmin\SqlParser\Tests\TestCase;
use PhpMyAdmin\SqlParser\Utils\Formatter;

class FormatterTest extends TestCase
{
    /**
     * @dataProvider mergeFormats
     *
     * @param mixed $default
     * @param mixed $overriding
     * @param mixed $expected
     */
    public function testMergeFormats($default, $overriding, $expected)
    {
        $formatter = $this->getMockBuilder('PhpMyAdmin\SqlParser\Utils\Formatter')
            ->disableOriginalConstructor()
            ->setMethods(array('getDefaultOptions', 'getDefaultFormats'))
            ->getMock();

        $formatter->expects($this->once())
            ->method('getDefaultOptions')
            ->willReturn(array(
                'type' => 'text',
                'line_ending' => null,
                'indentation' => null,
                'clause_newline' => null,
                'parts_newline' => null
            ));

        $formatter->expects($this->once())
            ->method('getDefaultFormats')
            ->willReturn($default);

        $expectedOptions = array(
            'type' => 'test-type',
            'line_ending' => '<br>',
            'indentation' => '    ',
            'clause_newline' => null,
            'parts_newline' => 0,
            'formats' => $expected
        );

        $overridingOptions = array(
            'type' => 'test-type',
            'line_ending' => '<br>',
            'formats' => $overriding
        );

        $reflectionMethod = new \ReflectionMethod($formatter, 'getMergedOptions');
        $reflectionMethod->setAccessible(true);
        $this->assertEquals($expectedOptions, $reflectionMethod->invoke($formatter, $overridingOptions));
    }

    public function mergeFormats()
    {
        // array($default[], $overriding[], $expected[])
        return array(
            'empty formats' => array(
                'default' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => '',
                        'cli' => '',
                        'function' => '',
                    ),
                ),
                'overriding' => array(
                    array(),
                ),
                'expected' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => '',
                        'cli' => '',
                        'function' => '',
                    ),
                ),
            ),
            'no flags' => array(
                'default' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                ),
                'overriding' => array(
                    array(
                        'type' => 0,
                        'html' => 'new html',
                        'cli' => 'new cli',
                    ),
                ),
                'expected' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'new html',
                        'cli' => 'new cli',
                        'function' => '',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                ),
            ),
            'with flags' => array(
                'default' => array(
                    array(
                        'type' => -1,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                ),
                'overriding' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'new html',
                        'cli' => 'new cli',
                    ),
                ),
                'expected' => array(
                    array(
                        'type' => -1,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'new html',
                        'cli' => 'new cli',
                        'function' => '',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                ),
            ),
            'with extra formats' => array(
                'default' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                ),
                'overriding' => array(
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'new html',
                        'cli' => 'new cli',
                    ),
                    array(
                        'type' => 1,
                        'html' => 'new html',
                        'cli' => 'new cli',
                    ),
                    array(
                        'type' => 1,
                        'flags' => 1,
                        'html' => 'new html',
                        'cli' => 'new cli',
                    ),
                ),
                'expected' => array(
                    array(
                        'type' => 0,
                        'flags' => 0,
                        'html' => 'html',
                        'cli' => 'cli',
                    ),
                    array(
                        'type' => 0,
                        'flags' => 1,
                        'html' => 'new html',
                        'cli' => 'new cli',
                        'function' => '',
                    ),
                    array(
                        'type' => 1,
                        'flags' => 0,
                        'html' => 'new html',
                        'cli' => 'new cli',
                        'function' => '',
                    ),
                    array(
                        'type' => 1,
                        'flags' => 1,
                        'html' => 'new html',
                        'cli' => 'new cli',
                        'function' => '',
                    ),
                ),
            )
        );
    }

    /**
     * @dataProvider formatQueries
     *
     * @param mixed $query
     * @param mixed $text
     * @param mixed $cli
     * @param mixed $html
     */
    public function testFormat($query, $text, $cli, $html, array $options = array())
    {
        // Test TEXT format
        $this->assertEquals($text, Formatter::format($query, array('type' => 'text') + $options), 'Text formatting failed.');

        // Test CLI format
        $this->assertEquals($cli, Formatter::format($query, array('type' => 'cli') + $options), 'CLI formatting failed.');

        // Test HTML format
        $this->assertEquals($html, Formatter::format($query, array('type' => 'html') + $options), 'HTML formatting failed.');
    }

    public function formatQueries()
    {
        return array(
            'empty' => array(
                'query' => '',
                'text' => '',
                'cli' => "\x1b[0m",
                'html' => '',
            ),
            'minimal' => array(
                'query' => 'select 1',
                'text' => 'SELECT' . "\n" .
                    '    1',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[92m1" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-number">1</span>',
            ),
            'simply' => array(
                'query' => 'select * from tbl where 1',
                'text' => 'SELECT' . "\n" .
                    '    *' . "\n" .
                    'FROM' . "\n" .
                    '    tbl' . "\n" .
                    'WHERE' . "\n" .
                    '    1',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[39m*" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[39mtbl" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[92m1" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;*' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;tbl' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-number">1</span>',
            ),
            'typical' => array(
                'query' => 'SELECT id, if(id=1,"Si","No") from `tbl` where id = 0 or id = 1 group by id order by id desc limit 1 offset 0',
                'text' => 'SELECT' . "\n" .
                    '    id,' . "\n" .
                    '    IF(id = 1, "Si", "No")' . "\n" .
                    'FROM' . "\n" .
                    '    `tbl`' . "\n" .
                    'WHERE' . "\n" .
                    '    id = 0 OR id = 1' . "\n" .
                    'GROUP BY' . "\n" .
                    '    id' . "\n" .
                    'ORDER BY' . "\n" .
                    '    id' . "\n" .
                    'DESC' . "\n" .
                    'LIMIT 1 OFFSET 0',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[39mid," . "\n" .
                    "    \x1b[35mIF\x1b[39m(id = \x1b[92m1\x1b[39m, \x1b[91m\"Si\"\x1b[39m, \x1b[91m\"No\"\x1b[39m)" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[36m`tbl`" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[39mid = \x1b[92m0 \x1b[35mOR \x1b[39mid = \x1b[92m1" . "\n" .
                    "\x1b[35mGROUP BY" . "\n" .
                    "    \x1b[39mid" . "\n" .
                    "\x1b[35mORDER BY" . "\n" .
                    "    \x1b[39mid" . "\n" .
                    "\x1b[35mDESC" . "\n" .
                    "LIMIT \x1b[92m1 \x1b[95mOFFSET \x1b[92m0" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;id,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-reserved">IF</span>(id = <span class="sql-number">1</span>, <span class="sql-string">"Si"</span>, <span class="sql-string">"No"</span>)' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`tbl`</span>' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;id = <span class="sql-number">0</span> <span class="sql-reserved">OR</span> id = <span class="sql-number">1</span>' . '<br/>' .
                    '<span class="sql-reserved">GROUP BY</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;id' . '<br/>' .
                    '<span class="sql-reserved">ORDER BY</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;id' . '<br/>' .
                    '<span class="sql-reserved">DESC</span>' . '<br/>' .
                    '<span class="sql-reserved">LIMIT</span> <span class="sql-number">1</span> <span class="sql-keyword">OFFSET</span> <span class="sql-number">0</span>',
            ),
            'comments' => array(
                'query' => 'select /* Comment */ *' . "\n" .
                    'from tbl # Comment' . "\n" .
                    'where 1 -- Comment',
                'text' => 'SELECT' . "\n" .
                    '    /* Comment */ *' . "\n" .
                    'FROM' . "\n" .
                    '    tbl # Comment' . "\n" .
                    'WHERE' . "\n" .
                    '    1 -- Comment',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[37m/* Comment */ \x1b[39m*" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[39mtbl \x1b[37m# Comment" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[92m1 \x1b[37m-- Comment" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-comment">/* Comment */</span> *' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;tbl <span class="sql-comment"># Comment</span>' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-number">1</span> <span class="sql-comment">-- Comment</span>',
            ),
            'strip comments' => array(
                'query' => 'select /* Comment */ *' . "\n" .
                    'from tbl # Comment' . "\n" .
                    'where 1 -- Comment',
                'text' => 'SELECT' . "\n" .
                    '    *' . "\n" .
                    'FROM' . "\n" .
                    '    tbl' . "\n" .
                    'WHERE' . "\n" .
                    '    1',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[39m*" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[39mtbl" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[92m1" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;*' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;tbl' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-number">1</span>',
                'options' => array(
                    'remove_comments' => true,
                ),
            ),
            'keywords' => array(
                'query' => 'select hex("1")',
                'text' => 'SELECT' . "\n" .
                    '    HEX("1")',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[95mHEX\x1b[39m(\x1b[91m\"1\"\x1b[39m)" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-keyword">HEX</span>(<span class="sql-string">"1"</span>)',
            ),
            'distinct count' => array(
                'query' => 'select distinct count(*)',
                'text' => 'SELECT DISTINCT' . "\n" .
                    '    COUNT(*)',
                'cli' => "\x1b[35mSELECT DISTINCT" . "\n" .
                    "    \x1b[95mCOUNT\x1b[39m(*)" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span> <span class="sql-reserved">DISTINCT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-keyword">COUNT</span>(*)',
            ),
            'create procedure' => array(
                'query' => 'create procedure test_procedure() begin from tbl select *; end',
                'text' => 'CREATE PROCEDURE test_procedure()' . "\n" .
                    'BEGIN' . "\n" .
                    '    FROM' . "\n" .
                    '        tbl' . "\n" .
                    '    SELECT' . "\n" .
                    '        *;' . "\n" .
                    'END',
                'cli' => "\x1b[35mCREATE PROCEDURE \x1b[39mtest_procedure()\n" .
                    "\x1b[95mBEGIN" . "\n" .
                    "    \x1b[35mFROM" . "\n" .
                    "        \x1b[39mtbl" . "\n" .
                    "    \x1b[35mSELECT" . "\n" .
                    "        \x1b[39m*;\n" .
                    "\x1b[95mEND" . "\x1b[0m",
                'html' => '<span class="sql-reserved">CREATE</span> <span class="sql-reserved">PROCEDURE</span> test_procedure()' . '<br/>' .
                    '<span class="sql-keyword">BEGIN</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tbl' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*;' . '<br/>' .
                    '<span class="sql-keyword">END</span>',
            ),
            'insert' => array(
                'query' => 'insert into foo values (0, 0, 0), (1, 1, 1)',
                'text' => 'INSERT INTO foo' . "\n" .
                    'VALUES(0, 0, 0),(1, 1, 1)',
                'cli' => "\x1b[35mINSERT INTO \x1b[39mfoo" . "\n" .
                    "\x1b[35mVALUES\x1b[39m(\x1b[92m0\x1b[39m, \x1b[92m0\x1b[39m, \x1b[92m0\x1b[39m),(\x1b[92m1\x1b[39m, \x1b[92m1\x1b[39m, \x1b[92m1\x1b[39m)" . "\x1b[0m",
                'html' => '<span class="sql-reserved">INSERT</span> <span class="sql-reserved">INTO</span> foo' . '<br/>' .
                    '<span class="sql-reserved">VALUES</span>(<span class="sql-number">0</span>, <span class="sql-number">0</span>, <span class="sql-number">0</span>),(<span class="sql-number">1</span>, <span class="sql-number">1</span>, <span class="sql-number">1</span>)',
            ),
            'string as alias' => array(
                'query' => 'select "Text" as bar',
                'text' => 'SELECT' . "\n" .
                    '    "Text" AS bar',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[91m\"Text\" \x1b[35mAS \x1b[39mbar" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-string">"Text"</span> <span class="sql-reserved">AS</span> bar',
            ),
            'escape cli' => array(
                'query' => "select 'text\x1b[33mcolor-inj'",
                'text' => 'SELECT' . "\n" .
                    "    'text\x1B[33mcolor-inj'",
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[91m'text\\x1B[33mcolor-inj'" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-string">\'text' . "\x1b[33m" . 'color-inj\'</span>',
            ),
            'escape html' => array(
                'query' => "select '<s>xss' from `<s>xss` , <s>nxss /*s<s>xss*/",
                'text' => 'SELECT' . "\n" .
                    '    \'<s>xss\'' . "\n" .
                    'FROM' . "\n" .
                    '    `<s>xss`,' . "\n" .
                    '    < s > nxss /*s<s>xss*/',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[91m'<s>xss'" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[36m`<s>xss`\x1b[39m," . "\n" .
                    "    < s > nxss \x1b[37m/*s<s>xss*/" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-string">\'&lt;s&gt;xss\'</span>' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`&lt;s&gt;xss`</span>,<br/>&nbsp;&nbsp;&nbsp;&nbsp;&lt; s &gt; nxss <span class="sql-comment">/*s&lt;s&gt;xss*/</span>',
            ),
            'create table' => array(
                'query' => 'create table if not exists `pma__bookmark` (' . "\n" .
                    '`id` int(11) not null auto_increment,' . "\n" .
                    '`dbase` varchar(255) not null default "",' . "\n" .
                    '`user` varchar(255) not null default "",' . "\n" .
                    '`label` varchar(255) collate utf8_general_ci not null default "",' . "\n" .
                    '`query` text not null,' . "\n" .
                    'primary key (`id`)',
                'text' => 'CREATE TABLE IF NOT EXISTS `pma__bookmark`(' . "\n" .
                    '    `id` INT(11) NOT NULL AUTO_INCREMENT,' . "\n" .
                    '    `dbase` VARCHAR(255) NOT NULL DEFAULT "",' . "\n" .
                    '    `user` VARCHAR(255) NOT NULL DEFAULT "",' . "\n" .
                    '    `label` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT "",' . "\n" .
                    '    `query` TEXT NOT NULL,' . "\n" .
                    '    PRIMARY KEY(`id`)',
                'cli' => "\x1b[35mCREATE TABLE IF NOT EXISTS \x1b[36m`pma__bookmark`\x1b[39m(" . "\n" .
                    "    \x1b[36m`id` \x1b[35mINT\x1b[39m(\x1b[92m11\x1b[39m) \x1b[35mNOT NULL \x1b[95mAUTO_INCREMENT\x1b[39m," . "\n" .
                    "    \x1b[36m`dbase` \x1b[35mVARCHAR\x1b[39m(\x1b[92m255\x1b[39m) \x1b[35mNOT NULL DEFAULT \x1b[91m\"\"\x1b[39m," . "\n" .
                    "    \x1b[36m`user` \x1b[35mVARCHAR\x1b[39m(\x1b[92m255\x1b[39m) \x1b[35mNOT NULL DEFAULT \x1b[91m\"\"\x1b[39m," . "\n" .
                    "    \x1b[36m`label` \x1b[35mVARCHAR\x1b[39m(\x1b[92m255\x1b[39m) \x1b[35mCOLLATE \x1b[39mutf8_general_ci \x1b[35mNOT NULL DEFAULT \x1b[91m\"\"\x1b[39m," . "\n" .
                    "    \x1b[36m`query` \x1b[95mTEXT \x1b[35mNOT NULL\x1b[39m," . "\n" .
                    "    \x1b[35mPRIMARY KEY\x1b[39m(\x1b[36m`id`\x1b[39m)" . "\x1b[0m",
                'html' => '<span class="sql-reserved">CREATE</span> <span class="sql-reserved">TABLE</span> <span class="sql-reserved">IF NOT EXISTS</span> <span class="sql-variable">`pma__bookmark`</span>(' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`id`</span> <span class="sql-reserved">INT</span>(<span class="sql-number">11</span>) <span class="sql-reserved">NOT NULL</span> <span class="sql-keyword">AUTO_INCREMENT</span>,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`dbase`</span> <span class="sql-reserved">VARCHAR</span>(<span class="sql-number">255</span>) <span class="sql-reserved">NOT NULL</span> <span class="sql-reserved">DEFAULT</span> <span class="sql-string">""</span>,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`user`</span> <span class="sql-reserved">VARCHAR</span>(<span class="sql-number">255</span>) <span class="sql-reserved">NOT NULL</span> <span class="sql-reserved">DEFAULT</span> <span class="sql-string">""</span>,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`label`</span> <span class="sql-reserved">VARCHAR</span>(<span class="sql-number">255</span>) <span class="sql-reserved">COLLATE</span> utf8_general_ci <span class="sql-reserved">NOT NULL</span> <span class="sql-reserved">DEFAULT</span> <span class="sql-string">""</span>,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-variable">`query`</span> <span class="sql-keyword">TEXT</span> <span class="sql-reserved">NOT NULL</span>,' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span class="sql-reserved">PRIMARY KEY</span>(<span class="sql-variable">`id`</span>)',
            ),
            'join' => array(
                'query' => 'join tbl2 on c1=c2',
                'text' => 'JOIN tbl2 ON c1 = c2',
                'cli' => "\x1b[35mJOIN \x1b[39mtbl2 \x1b[35mON \x1b[39mc1 = c2" .
                    "\x1b[0m",
                'html' => '<span class="sql-reserved">JOIN</span> tbl2 <span class="sql-reserved">ON</span> c1 = c2',
            ),
            'named param' => array(
                'query' => 'select * from tbl where col = :param',
                'text' => 'SELECT' . "\n" .
                    '    *' . "\n" .
                    'FROM' . "\n" .
                    '    tbl' . "\n" .
                    'WHERE' . "\n" .
                    '    col = :param',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[39m*" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[39mtbl" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[39mcol = \x1b[31m:param" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;*' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;tbl' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;col = <span class="sql-parameter">:param</span>',
            ),
            'anon param' => array(
                'query' => 'select * from tbl where col = ?',
                'text' => 'SELECT' . "\n" .
                    '    *' . "\n" .
                    'FROM' . "\n" .
                    '    tbl' . "\n" .
                    'WHERE' . "\n" .
                    '    col = ?',
                'cli' => "\x1b[35mSELECT" . "\n" .
                    "    \x1b[39m*" . "\n" .
                    "\x1b[35mFROM" . "\n" .
                    "    \x1b[39mtbl" . "\n" .
                    "\x1b[35mWHERE" . "\n" .
                    "    \x1b[39mcol = \x1b[31m?" . "\x1b[0m",
                'html' => '<span class="sql-reserved">SELECT</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;*' . '<br/>' .
                    '<span class="sql-reserved">FROM</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;tbl' . '<br/>' .
                    '<span class="sql-reserved">WHERE</span>' . '<br/>' .
                    '&nbsp;&nbsp;&nbsp;&nbsp;col = <span class="sql-parameter">?</span>',
            )
        );
    }
}
