#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanTasks();
timeout=0
cid=0

- 查询 repo1 plan1 的任务列表 @0,1,20
- 查询 repo2 plan2 的任务列表 @0,1,20
- 查询默认 repo 和 plan 的任务列表 @0,1,20
- 查询 repo1 plan2 的任务列表 @0,1,20
- 查询 repo2 plan1 的任务列表 @0,1,20

*/

$test = new codescanModelTest();

r($test->getScanTasksTest(1, 1, array(1, 2, 3))) && p('pager:total,page,pageSize') && e('0,1,20');
r($test->getScanTasksTest(2, 2, array())) && p('pager:total,page,pageSize') && e('0,1,20');
r($test->getScanTasksTest(0, 0, array(1))) && p('pager:total,page,pageSize') && e('0,1,20');
r($test->getScanTasksTest(1, 2, array(1, 2))) && p('pager:total,page,pageSize') && e('0,1,20');
r($test->getScanTasksTest(2, 1, array(1, 2, 3, 4))) && p('pager:total,page,pageSize') && e('0,1,20');
