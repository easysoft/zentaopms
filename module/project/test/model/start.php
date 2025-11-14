#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project');
$project->status->range('wait,doing,suspend,closed');
$project->gen(15);

/**

title=测试 projectModel->start();
timeout=0
cid=17869

- 挂起的项目第0条的new属性 @doing
- 关闭的项目第0条的new属性 @doing
- 未开始的项目第0条的new属性 @doing
- 进行中的项目（状态没有差异只有实际开始时间不同）第0条的new属性 @2022-10-10
- 挂起的项目2第0条的new属性 @doing

*/
global $tester;
$tester->loadModel('project');

$_POST['uid'] = '0';

$data = new stdclass();
$data->status    = 'doing';
$data->realBegan = '2022-10-10';
$data->uid       = null;

r($tester->project->start(11, $data)) && p('0:new')   && e('doing');      // 挂起的项目
r($tester->project->start(12, $data)) && p('0:new')   && e('doing');      // 关闭的项目
r($tester->project->start(13, $data)) && p('0:new')   && e('doing');      // 未开始的项目
r($tester->project->start(14, $data)) && p('0:new')   && e('2022-10-10'); // 进行中的项目（状态没有差异只有实际开始时间不同）
r($tester->project->start(15, $data)) && p('0:new')   && e('doing');      // 挂起的项目2
