#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('story')->gen(20);
$file = zdTable('file');
$file->objectType->range('task');
$file->gen(10);

$task = zdTable('task');
$task->project->range('11');
$task->parent->range('0{3},3{7}');
$task->mode->range('linear{1},multi{1},0{8}');
$task->execution->range('101');
$task->gen(10);

su('admin');

/**

title=测试 transfer->updateChildDatas();
timeout=0
cid=1

*/
$transfer = new transferTest();

/* 测试导出任务。*/
r($transfer->updateChildDatasTest('task')) && p('0:name') && e('[多人] 开发任务11'); // 测试导出多人并行任务
r($transfer->updateChildDatasTest('task')) && p('1:name') && e('[多人] 开发任务12'); // 测试导出多人串行任务
r($transfer->updateChildDatasTest('task')) && p('2:name') && e('开发任务13');        // 测试导出正常任务
r($transfer->updateChildDatasTest('task')) && p('3:name') && e('>开发任务14');       // 测试导出子任务
r(isset($transfer->updateChildDatasTest('task')[8]->files)) && p('') && e('1');      // 测试导出附件是否存在
