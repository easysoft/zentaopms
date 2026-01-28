#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('project')->gen(50);
zenData('product')->gen(10);
zenData('module')->gen(10);
$task = zenData('task');
$task->execution->range('101');
$task->gen(50);
su('admin');

/**

title=- 测试导出任务时所属项目字段第rows[0]条的project属性 @项目11(
timeout=0
cid=19312

- 测试导出任务时任务名称字段第rows[0]条的name属性 @开发任务11
- 测试导出任务的状态字段第rows[0]条的status属性 @wait
- 测试导出任务时所属执行字段第listStyle条的1属性 @execution
- 测试导出模块的值属性kind @task
- 测试导出数量属性count @42

*/

$transfer = new transferModelTest();

r($transfer->exportTest('task')) && p('rows[0]:name')   && e('开发任务11'); // 测试导出任务时任务名称字段
r($transfer->exportTest('task')) && p('rows[0]:status') && e('wait');       // 测试导出任务的状态字段
r($transfer->exportTest('task')) && p('listStyle:1')    && e('execution');  // 测试导出任务时所属执行字段
r($transfer->exportTest('task')) && p('kind')           && e('task');       // 测试导出模块的值
r($transfer->exportTest('task')) && p('count')          && e('42');         // 测试导出数量