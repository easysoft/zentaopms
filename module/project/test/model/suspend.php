#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zdTable('project');
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
cid=1

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

r($tester->project->suspend(2, $project)) && p('0:new') && e('suspended');
r($tester->project->suspend(4, $project)) && p('0:new') && e('suspended');
