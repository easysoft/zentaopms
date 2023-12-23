#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zdTable('project');
    $project->id->range('1-5');
    $project->project->range('1-5');
    $project->name->prefix('项目')->range('1-5');
    $project->code->prefix('project')->range('1-5');
    $project->type->range('project');
    $project->status->range('wait,doing,suspended,closed');

    $project->gen(5);
}

/**

title=测试 projectModel->close();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

initData();
$_POST['uid']     = '0';
$_POST['realEnd'] = '2022-05-03';

$data = new stdclass();
$data->status   = 'closed';
$data->realEnd  = '2022-10-10';
$data->closedBy = 'admin';

$changes1 = $tester->project->close(1, $data);
$changes2 = $tester->project->close(5, $data);
$changes3 = $tester->project->close(3, $data);

$newPorject = $tester->project->getByID(3);
$closedBy   = $newPorject->closedBy;
$realEnd    = $newPorject->realEnd;

r($changes1) && p('0:field,old,new') && e('status,wait,closed');
r($changes3) && p('0:field,old,new') && e('status,suspended,closed');
r($closedBy) && p() && e('admin');      //查看执行关闭后关闭者变更为admin
r($realEnd)  && p() && e('2022-10-10'); //查看执行关闭后真实结束时间为'2022-10-10'
