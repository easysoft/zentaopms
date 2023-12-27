#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('product')->gen(10);
zdTable('project')->gen(50);
zdTable('module')->gen(10);
zdTable('task')->gen(100);
su('admin');

/**

title=测试 transfer->initValues();
timeout=0
cid=1

- 测试初始化导出任务时所属项目字段属性11 @项目11
- 测试初始化导出任务时优先级字段属性1 @1

*/

$transfer = new transferTest();

r($transfer->initValuesTest('task', false, 'project')) && p('11') && e('项目11'); // 测试初始化导出任务时所属项目字段
r($transfer->initValuesTest('task', false, 'pri'))     && p('1')  && e('1');      // 测试初始化导出任务时优先级字段
