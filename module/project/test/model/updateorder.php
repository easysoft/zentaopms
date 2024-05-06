#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('1-5');
    $project->gen(5);
}

/**

title=测试 projectModel::suspend;
timeout=0
cid=1

- 执行$project1 @1

- 执行$project2 @1



*/

global $tester;
$tester->loadModel('project');

initData();

$idList  = array(1,2,3,4,5);
$order1  = 'id_asc';
$order2  = 'id_desc';
$project1 = $tester->project->updateOrder($idList, $order1);
$project2 = $tester->project->updateOrder($idList, $order2);

r($project1) && p() && e('1');
r($project2) && p() && e('1');