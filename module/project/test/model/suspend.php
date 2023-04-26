#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $project = zdTable('project');
    $project->id->range('2-5');
    $project->project->range('2-5');
    $project->name->prefix("项目")->range('2-5');
    $project->code->prefix("project")->range('2-5');
    $project->type->range("project");
    $project->status->range("doing,suspended,closed");

    $project->gen(4);
}

/**

title=测试 projectModel->suspend();
timeout=0
cid=1

- 执行project模块的suspend方法，参数是2,属性new @suspended
- 执行project模块的suspend方法，参数是5,属性field @suspendedDate
- 执行project模块的suspend方法，参数是4,属性new @suspended


*/

global $tester;
$tester->loadModel('project');

initData();

r($tester->project->suspend(2)) && p('new')   && e('suspended');
r($tester->project->suspend(5)) && p('field') && e('suspendedDate');
r($tester->project->suspend(4)) && p('new')   && e('suspended');