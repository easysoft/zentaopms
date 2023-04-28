#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $project = zdTable('project');
    $project->id->range('1-5');
    $project->project->range('1-5');
    $project->name->prefix("项目")->range('1-5');
    $project->code->prefix("project")->range('1-5');
    $project->type->range("project");
    $project->status->range("wait,doing,suspended,closed");

    $project->gen(5);
}

/**

title=测试 projectModel->close();
timeout=0
cid=1

- 执行$changes1
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @closed

- 执行$changes2
 - 第2条的field属性 @closedBy
 - 第2条的old属性 @~~
 - 第2条的new属性 @admin

- 执行$changes3
 - 第0条的field属性 @status
 - 第0条的old属性 @suspended
 - 第0条的new属性 @closed

- 执行$changes3
 - 第1条的field属性 @realEnd
 - 第1条的old属性 @0000-00-00
 - 第1条的new属性 @2022-10-10



*/

global $tester;
$tester->loadModel('project');

initData();
$_POST['realEnd'] = '2022-05-03';

$data = new stdclass();
$data->status   = 'closed';
$data->realEnd  = '2022-10-10';
$data->closedBy = 'admin';

$changes1 = $tester->project->close(1, $data);
$changes2 = $tester->project->close(5, $data);
$changes3 = $tester->project->close(3, $data);

r($changes1) && p('0:field,old,new') && e('status,wait,closed');
r($changes2) && p('2:field,old,new') && e('closedBy,~~,admin');
r($changes3) && p('0:field,old,new') && e('status,suspended,closed');
r($changes3) && p('1:field,old,new') && e('realEnd,0000-00-00,2022-10-10');
