#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';
zenData('project')->gen(50);
zenData('product')->gen(10);
zenData('module')->gen(10);
$task = zenData('task');
$task->project->range('11');
$task->execution->range('101');
$task->gen(50);
su('admin');

/**

title=测试 transfer->generateExportDatas();
timeout=0
cid=19314

- 测试导出任务预计工时第41条的estimate属性 @5
- 测试导出任务消耗工时第41条的consumed属性 @12
- 测试导出任务剩余工时第41条的left属性 @5
- 测试导出任务类型第41条的type属性 @开发(#devel)
- 测试导出任务所属项目第41条的project属性 @项目11(#11)

*/

$transfer = new transferTest();

r($transfer->generateExportDatasTest('task')) && p('41:estimate') && e('5');            // 测试导出任务预计工时
r($transfer->generateExportDatasTest('task')) && p('41:consumed') && e('12');           // 测试导出任务消耗工时
r($transfer->generateExportDatasTest('task')) && p('41:left')     && e('5');            // 测试导出任务剩余工时
r($transfer->generateExportDatasTest('task')) && p('41:type')     && e('开发(#devel)'); // 测试导出任务类型
r($transfer->generateExportDatasTest('task')) && p('41:project')  && e('项目11(#11)');  // 测试导出任务所属项目
