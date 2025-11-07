#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkDuplicateKeys();
timeout=0
cid=0

- 执行customTest模块的checkDuplicateKeysTest方法，参数是'story', 'priList', array  @1
- 执行customTest模块的checkDuplicateKeysTest方法，参数是'story', 'priList', array 属性message @key1键重复
- 执行customTest模块的checkDuplicateKeysTest方法，参数是'testtask', 'typeList', array  @1
- 执行customTest模块的checkDuplicateKeysTest方法，参数是'bug', 'severityList', array 属性message @2键重复
- 执行customTest模块的checkDuplicateKeysTest方法，参数是'story', 'priList', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$customTest = new customZenTest();

r($customTest->checkDuplicateKeysTest('story', 'priList', array('1', '2', '3', '4'))) && p() && e('1');
r($customTest->checkDuplicateKeysTest('story', 'priList', array('key1', 'key2', 'key1', 'key3'))) && p('message') && e('key1键重复');
r($customTest->checkDuplicateKeysTest('testtask', 'typeList', array('', 'key1', '', 'key2'))) && p() && e('1');
r($customTest->checkDuplicateKeysTest('bug', 'severityList', array('1', '2', '3', '2', '4'))) && p('message') && e('2键重复');
r($customTest->checkDuplicateKeysTest('story', 'priList', array())) && p() && e('1');