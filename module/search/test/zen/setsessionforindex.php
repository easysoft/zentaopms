#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setSessionForIndex();
timeout=0
cid=0

- 测试步骤1:正常输入完整参数验证bugList session属性bugList @测试URI地址
- 测试步骤2:正常输入完整参数验证taskList session属性taskList @测试URI地址
- 测试步骤3:正常输入完整参数验证searchIngWord属性searchIngWord @测试关键词
- 测试步骤4:传入空uri验证searchIngWord属性searchIngWord @空字符串关键词
- 测试步骤5:传入空words验证searchIngWord属性searchIngWord @~~
- 测试步骤6:type为字符串类型验证searchIngType为字符串属性searchIngType @bug
- 测试步骤7:type为数组类型验证searchIngType为数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$searchTest = new searchZenTest();

r($searchTest->setSessionForIndexTest('测试URI地址', '测试关键词', 'bug')) && p('bugList') && e('测试URI地址'); // 测试步骤1:正常输入完整参数验证bugList session
r($searchTest->setSessionForIndexTest('测试URI地址', '测试关键词', 'task')) && p('taskList') && e('测试URI地址'); // 测试步骤2:正常输入完整参数验证taskList session
r($searchTest->setSessionForIndexTest('测试URI地址', '测试关键词', 'product')) && p('searchIngWord') && e('测试关键词'); // 测试步骤3:正常输入完整参数验证searchIngWord
r($searchTest->setSessionForIndexTest('', '空字符串关键词', 'bug')) && p('searchIngWord') && e('空字符串关键词'); // 测试步骤4:传入空uri验证searchIngWord
r($searchTest->setSessionForIndexTest('测试URI', '', 'task')) && p('searchIngWord') && e('~~'); // 测试步骤5:传入空words验证searchIngWord
r($searchTest->setSessionForIndexTest('URI地址', 'word', 'bug')) && p('searchIngType') && e('bug'); // 测试步骤6:type为字符串类型验证searchIngType为字符串
r(is_array($searchTest->setSessionForIndexTest('URI地址', 'word', array('bug', 'task'))->searchIngType)) && p() && e('1'); // 测试步骤7:type为数组类型验证searchIngType为数组