#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('project')->gen(50);
zdTable('product')->gen(10);
zdTable('module')->gen(10);
$task = zdTable('task');
$task->execution->range('101');
$task->gen(50);
su('admin');

/**

title=测试 transfer->export();
timeout=0
cid=1

- 测试导出任务时所属项目字段第rows[0]条的name属性 @开发任务11
- 测试导出任务时所属执行字段第listStyle条的1属性 @execution
- 测试导出模块的值属性kind @task
- 测试导出数量属性count @42

*/

$transfer = new transferTest();

r($transfer->exportTest('task')) && p('rows[0]:name') && e('开发任务11'); //测试导出任务时所属项目字段
r($transfer->exportTest('task')) && p('listStyle:1')  && e('execution');  //测试导出任务时所属执行字段
r($transfer->exportTest('task')) && p('kind')         && e('task');       //测试导出模块的值
r($transfer->exportTest('task')) && p('count')        && e('42');         //测试导出数量
