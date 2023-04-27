#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zdTable('project');
$project->gen(5);

/**

title=测试 projectModel->start();
timeout=0
cid=1

- 执行project模块的start方法，参数是11, $data- ,属性0 @status
 @status
- 执行project模块的start方法，参数是12, $data- ,属性0 @doing
 @doing
- 执行project模块的start方法，参数是13, $data
 - 属性name @0
 - 属性status @0

- 执行project模块的start方法，参数是14, $data @0
- 执行project模块的start方法，参数是15, $data @0


*/
global $tester;
$tester->loadModel('project');

$data = new stdclass();
$data->status    = 'doing';
$data->realBegan = '2022-10-10';

r($tester->project->start(11, $data)) && p('0:field')     && e('status');
r($tester->project->start(12, $data)) && p('0:new')       && e('doing');
r($tester->project->start(13, $data)) && p('name,status') && e('0,0');
r($tester->project->start(14, $data)) && p()              && e('0');
r($tester->project->start(15, $data)) && p()              && e('0');