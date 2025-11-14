#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('2-5');
    $project->project->range('2-5');
    $project->name->prefix("项目")->range('2-5');
    $project->code->prefix("project")->range('2-5');
    $project->type->range("project");
    $project->status->range("doing,closed");

    $project->gen(4);
}

/**
title=测试 projectModel::suspend();
timeout=0
cid=17870

- 暂停 projectID=2 后，检查$changes[0]
 - 属性field @status
 - 属性new @suspended
- 暂停 projectID=2 后，检查$changes[1]
 - 属性field @suspendedDate
 - 属性new @2023-05-03
- 暂停 projectID=4第0条的new属性 @suspended
- 执行已经暂停的项目 @0

*/

global $tester;
$_POST['uid'] = '0';
$tester->loadModel('project');

initData();

$project =  new stdClass;
$project->status         = 'suspended';
$project->lastEditedBy   = 'admin';
$project->lastEditedDate = '2023-04-27';
$project->suspendedDate  = '2023-05-03';

$changes = $tester->project->suspend(2, $project);
r($changes[0])                            && p('field,new') && e('status,suspended');          // 暂停 projectID=2 后，检查$changes[0]
r($changes[1])                            && p('field,new') && e('suspendedDate,2023-05-03');  // 暂停 projectID=2 后，检查$changes[1]
r($tester->project->suspend(4, $project)) && p('0:new')     && e('suspended');                 // 暂停 projectID=4
r($tester->project->suspend(4, $project)) && p()            && e('0');                         // 执行已经暂停的项目
