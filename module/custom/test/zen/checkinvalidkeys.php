#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkInvalidKeys();
timeout=0
cid=0

- 执行customTest模块的checkInvalidKeysTest方法，参数是'story', 'priList', 'zh-cn', array  @1
- 执行customTest模块的checkInvalidKeysTest方法，参数是'bug', 'severityList', 'zh-cn', array 属性message @键值应为不大于255的数字
- 执行customTest模块的checkInvalidKeysTest方法，参数是'story', 'sourceList', 'zh-cn', array 属性message @键值应当为大小写英文字母、数字或下划线的组合
- 执行customTest模块的checkInvalidKeysTest方法，参数是'user', 'roleList', 'zh-cn', array 属性message @键的长度必须小于10个字符！
- 执行customTest模块的checkInvalidKeysTest方法，参数是'todo', 'typeList', 'zh-cn', array 属性message @键的长度必须小于15个字符！
- 执行customTest模块的checkInvalidKeysTest方法，参数是'story', 'sourceList', 'zh-cn', array 属性message @键的长度必须小于20个字符！
- 执行customTest模块的checkInvalidKeysTest方法，参数是'story', 'sourceList', 'zh-cn', array  @1
- 执行customTest模块的checkInvalidKeysTest方法，参数是'story', 'priList', 'zh-cn', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$customTest = new customZenTest();

r($customTest->checkInvalidKeysTest('story', 'priList', 'zh-cn', array('1', '2', '3', '100'))) && p() && e('1');
r($customTest->checkInvalidKeysTest('bug', 'severityList', 'zh-cn', array('1', '256'))) && p('message') && e('键值应为不大于255的数字');
r($customTest->checkInvalidKeysTest('story', 'sourceList', 'zh-cn', array('valid_key', 'invalid-key'))) && p('message') && e('键值应当为大小写英文字母、数字或下划线的组合');
r($customTest->checkInvalidKeysTest('user', 'roleList', 'zh-cn', array('short', 'verylongkey123'))) && p('message') && e('键的长度必须小于10个字符！');
r($customTest->checkInvalidKeysTest('todo', 'typeList', 'zh-cn', array('short', 'verylongtypekey1'))) && p('message') && e('键的长度必须小于15个字符！');
r($customTest->checkInvalidKeysTest('story', 'sourceList', 'zh-cn', array('valid_key', 'verylongsourcekey1234'))) && p('message') && e('键的长度必须小于20个字符！');
r($customTest->checkInvalidKeysTest('story', 'sourceList', 'zh-cn', array('valid_key', 'test123', 'ABC_def'))) && p() && e('1');
r($customTest->checkInvalidKeysTest('story', 'priList', 'zh-cn', array())) && p() && e('1');