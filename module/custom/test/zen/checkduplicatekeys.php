#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkDuplicateKeys();
timeout=0
cid=0

- 执行customTest模块的checkDuplicateKeysTest方法，参数是array  @1
- 执行customTest模块的checkDuplicateKeysTest方法，参数是array  @1键重复
- 执行customTest模块的checkDuplicateKeysTest方法，参数是array  @1
- 执行customTest模块的checkDuplicateKeysTest方法，参数是array  @key1键重复
- 执行customTest模块的checkDuplicateKeysTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

su('admin');

$customTest = new customTest();

r($customTest->checkDuplicateKeysTest(array('1', '2', '3'), 'story', 'priList')) && p() && e('1');
r($customTest->checkDuplicateKeysTest(array('1', '2', '1'), 'story', 'priList')) && p() && e('1键重复');
r($customTest->checkDuplicateKeysTest(array('', '1', '2'), 'testtask', 'typeList')) && p() && e('1');
r($customTest->checkDuplicateKeysTest(array('key1', 'key2', 'key1'), 'bug', 'statusList')) && p() && e('key1键重复');
r($customTest->checkDuplicateKeysTest(array(''), 'story', 'priList')) && p() && e('1');