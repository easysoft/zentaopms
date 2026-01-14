#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('product')->gen(10);
zenData('project')->gen(50);
zenData('module')->gen(10);
zenData('task')->gen(100);
su('admin');

/**

title=测试 transfer->initItems();
timeout=0
cid=19322

- 测试初始化导出任务时所属项目字段属性11 @项目11
- 测试初始化导出任务时优先级字段属性1 @1

*/

$transfer = new transferModelTest();

r($transfer->initItemsTest('task', false, 'project')) && p('11,12,13,14') && e('项目11,项目12,项目13,项目14'); // 测试初始化导出任务时所属项目字段
r($transfer->initItemsTest('task', false, 'pri'))     && p('1')           && e('1'); // 测试初始化导出任务时优先级字段