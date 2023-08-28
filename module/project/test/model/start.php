#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->status->range('wait,doing,suspend,closed');
$project->gen(15);

/**

title=测试 projectModel->start();
timeout=0
cid=1

*/
global $tester;
$tester->loadModel('project');

$data = new stdclass();
$data->status    = 'doing';
$data->realBegan = '2022-10-10';

r($tester->project->start(11, $data)) && p('0:field') && e('status'); // 挂起的项目
r($tester->project->start(12, $data)) && p('0:new')   && e('doing');  // 关闭的项目
r($tester->project->start(13, $data)) && p('0:old')   && e('wait');   // 未开始的项目
r($tester->project->start(14, $data)) && p()          && e('0');      // 进行中的项目
