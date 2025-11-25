#!/usr/bin/env php
<?php

/**

title=测试 ciModel::transformAnsiToHtml();
timeout=0
cid=15595

- 执行ciTest模块的transformAnsiToHtmlTest方法，参数是'normal text'  @normal text
- 执行ciTest模块的transformAnsiToHtmlTest方法，参数是"\x1B[31;40mred text\x1B[0;m"  @<font style="color: red">red text</font><br>
- 执行ciTest模块的transformAnsiToHtmlTest方法，参数是"\x1B[32;1mgreen text\x1B[0;m"  @<font style="color: green">green text</font><br>
- 执行ciTest模块的transformAnsiToHtmlTest方法，参数是"\x1B[1mbold\x1B[0;m and \x1B[36;1mcyan\x1B[0;m text"  @<font style="font-weight:bold">bold</font><br> and <font style="color: cyan">cyan</font><br> text
- 执行ciTest模块的transformAnsiToHtmlTest方法，参数是"Line1\x1B[0KLine2\x1B[0;33myellow\x1B[0;m"  @Line1<br>Line2<font style="color: yellow">yellow</font><br>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

su('admin');

$ciTest = new ciTest();

r($ciTest->transformAnsiToHtmlTest('normal text')) && p() && e('normal text');
r($ciTest->transformAnsiToHtmlTest("\x1B[31;40mred text\x1B[0;m")) && p() && e('<font style="color: red">red text</font><br>');
r($ciTest->transformAnsiToHtmlTest("\x1B[32;1mgreen text\x1B[0;m")) && p() && e('<font style="color: green">green text</font><br>');
r($ciTest->transformAnsiToHtmlTest("\x1B[1mbold\x1B[0;m and \x1B[36;1mcyan\x1B[0;m text")) && p() && e('<font style="font-weight:bold">bold</font><br> and <font style="color: cyan">cyan</font><br> text');
r($ciTest->transformAnsiToHtmlTest("Line1\x1B[0KLine2\x1B[0;33myellow\x1B[0;m")) && p() && e('Line1<br>Line2<font style="color: yellow">yellow</font><br>');