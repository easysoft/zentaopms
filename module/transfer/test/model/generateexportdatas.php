#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('project')->gen(50);
zdTable('product')->gen(10);
zdTable('module')->gen(10);
$task = zdTable('task');
$task->project->range('11');
$task->execution->range('101');
$task->gen(50);
su('admin');

/**

title=测试 transfer->generateExportDatas();
timeout=0
cid=1

*/

$transfer = new transferTest();

r($transfer->generateExportDatasTest('task')) && p('41:type') && e('开发');           // 测试导出任务类型
r($transfer->generateExportDatasTest('task')) && p('41:project') && e('项目11(#11)'); // 测试导出任务所属项目
