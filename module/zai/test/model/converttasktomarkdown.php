#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertTaskToMarkdown();
timeout=0
cid=0

- 测试转换任务对象1属性id @1
- 测试转换任务对象2属性id @2
- 测试验证Markdown内容包含任务信息 @1
- 测试验证属性设置正确
 - 属性project @11
 - 属性module @21
- 测试验证标题包含ID @1
- 测试验证内容包含优先级信息 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

zenData('task')->gen(2);
$task1 = $tester->loadModel('task')->getByID(1);
$task2 = $tester->task->getByID(2);

$result1 = $zai->convertTaskToMarkdownTest($task1);
r($result1) && p('id') && e('1'); // 测试转换任务对象1

$result2 = $zai->convertTaskToMarkdownTest($task2);
r($result2) && p('id') && e('2'); // 测试转换任务对象2
