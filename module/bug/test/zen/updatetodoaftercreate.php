#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateTodoAfterCreate();
timeout=0
cid=15483

- 测试正常更新待办状态 @1
- 测试另一个待办更新 @1
- 测试待办类型为feedback的情况 @1
- 测试不存在的待办ID @1
- 测试第5个待办更新 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('todo')->loadYaml('updatetodoaftercreate/todo', false, 2)->gen(10);
zenData('action')->gen(0);
zenData('bug')->gen(10);
zenData('user')->gen(10);
zenData('feedback')->gen(0);

su('admin');

global $tester;
$bugTest = new bugZenTest();

r($bugTest->updateTodoAfterCreateTest(1, 1)) && p() && e('1'); // 测试正常更新待办状态
r($bugTest->updateTodoAfterCreateTest(2, 2)) && p() && e('1'); // 测试另一个待办更新
r($bugTest->updateTodoAfterCreateTest(3, 10)) && p() && e('1'); // 测试待办类型为feedback的情况
r($bugTest->updateTodoAfterCreateTest(4, 999)) && p() && e('1'); // 测试不存在的待办ID
r($bugTest->updateTodoAfterCreateTest(5, 5)) && p() && e('1'); // 测试第5个待办更新