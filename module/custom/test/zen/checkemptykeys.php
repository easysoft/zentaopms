#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkEmptyKeys();
timeout=0
cid=0

- 执行customTest模块的checkEmptyKeysTest方法，参数是'story', 'priList', 'zh-cn', array  @1
- 执行customTest模块的checkEmptyKeysTest方法，参数是'bug', 'severityList', 'zh-cn', array  @1
- 执行customTest模块的checkEmptyKeysTest方法，参数是'task', 'typeList', 'en', array  @1
- 执行customTest模块的checkEmptyKeysTest方法，参数是'story', 'sourceList', 'zh-cn', array 属性message @值不能为空！
- 执行customTest模块的checkEmptyKeysTest方法，参数是'testcase', 'typeList', 'zh-cn', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$customTest = new customZenTest();

r($customTest->checkEmptyKeysTest('story', 'priList', 'zh-cn', array('1', '2', '3'), array('高', '中', '低'), array('1', '1', '1'))) && p() && e('1');
r($customTest->checkEmptyKeysTest('bug', 'severityList', 'zh-cn', array('1', '2', ''), array('严重', '一般', '轻微'), array('1', '1', '0'))) && p() && e('1');
r($customTest->checkEmptyKeysTest('task', 'typeList', 'en', array('', 'design', ''), array('', 'Design', ''), array('0', '1', '0'))) && p() && e('1');
r($customTest->checkEmptyKeysTest('story', 'sourceList', 'zh-cn', array('customer', 'market', ''), array('客户', '', '其他'), array('1', '1', '0'))) && p('message') && e('值不能为空！');
r($customTest->checkEmptyKeysTest('testcase', 'typeList', 'zh-cn', array(), array(), array())) && p() && e('1');