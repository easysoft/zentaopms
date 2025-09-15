#!/usr/bin/env php
<?php

/**

title=测试 aiModel::tryPunctuate();
timeout=0
cid=0

- 执行aiTest模块的tryPunctuateTest方法，参数是'Hello world!', false  @Hello world!
- 执行aiTest模块的tryPunctuateTest方法，参数是'Hello world', false  @Hello world.
- 执行aiTest模块的tryPunctuateTest方法，参数是'', false  @0
- 执行aiTest模块的tryPunctuateTest方法，参数是'Hello world.', false  @Hello world.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->tryPunctuateTest('Hello world!', false)) && p() && e('Hello world!');
r($aiTest->tryPunctuateTest('Hello world', false)) && p() && e('Hello world.');
r(strlen($aiTest->tryPunctuateTest('', false))) && p() && e('0');
r($aiTest->tryPunctuateTest('Hello world', true)) && p() && e('Hello world.
');
r($aiTest->tryPunctuateTest('Hello world.', false)) && p() && e('Hello world.');