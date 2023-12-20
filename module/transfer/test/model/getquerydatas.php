#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('story')->gen(20);
$task = zdTable('task');
$task->project->range('11');
$task->execution->range('101');
$task->gen(10);

su('admin');

/**

title=测试 transfer->getQueryDatas();
timeout=0
cid=1

- 测试导出需求第14条的title属性 @软件需求14
- 测试导出task数据第10条的desc属性 @这里是任务描述10
- 测试task导出选中记录第3条的desc属性 @这里是任务描述3
- 测试task导出选中记录的数量 @3

*/
$transfer = new transferTest();

/* 测试导出需求。*/
r($transfer->getQueryDatasTest('story')) && p('14:title') && e("软件需求14"); // 测试导出需求

/* 测试导出任务。*/
r($transfer->getQueryDatasTest('task'))          && p('10:desc') && e("这里是任务描述10"); // 测试导出task数据
r($transfer->getQueryDatasTest('task', '1,2,3')) && p('3:desc')  && e("这里是任务描述3");  // 测试task导出选中记录
r(count($transfer->getQueryDatasTest('task', '1,2,3'))) && p('') && e("3");                // 测试task导出选中记录的数量
