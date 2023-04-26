#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

$project = zdTable('project');
$project->gen(5);

/**

title=测试 projectModel->start();
timeout=0
cid=1
pid=1

- 执行project模块的start方法，参数是11- ,属性0 @status >> status
 @status >> doing
- 执行project模块的start方法，参数是12- ,属性0 @doing >> 0
 @doing >> 0
- 执行project模块的start方法，参数是13, 'task >> 0

*/
global $tester;
$tester->loadModel('project');

$data = new stdclass();
$data->status    = 'doing';
$data->realBegan = '2022-10-10';

r($tester->project->start(11, $data)) && p('0:field')     && e('status');
r($tester->project->start(12, $data)) && p('0:new')       && e('doing');
r($tester->project->start(13, $data)) && p('name,status') && e('0');
r($tester->project->start(14, $data)) && p()              && e('0');
r($tester->project->start(15, $data)) && p()              && e('0');
