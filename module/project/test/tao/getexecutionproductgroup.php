#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('1,2');
    $project->gen(2);

    $product = zenData('product')->gen(3);

    $projectproduct = zenData('projectproduct');
    $projectproduct->project->range('1{3},2{2}');
    $projectproduct->product->range('1,2,3');
    $projectproduct->gen(5);
}

/**

title=测试 projectTao::getExecutionProductGroup();
timeout=0
cid=17909

- 执行$project1[1][1]
 - 属性project @1
 - 属性product @1

- 执行$project1[1][2]
 - 属性project @1
 - 属性product @2

- 执行$project1[1][3]
 - 属性project @1
 - 属性product @3

- 执行$project2[2][1]
 - 属性project @2
 - 属性product @1

- 执行$project2[2][2]
 - 属性project @2
 - 属性product @2



*/

initData();

global $tester;

$executions1 = array(1,2);
$executions2 = array(2);
$project1    = $tester->loadModel('project')->getExecutionProductGroup($executions1);
$project2    = $tester->loadModel('project')->getExecutionProductGroup($executions2);

r($project1[1][1]) && p('project,product') && e('1,1');
r($project1[1][2]) && p('project,product') && e('1,2');
r($project1[1][3]) && p('project,product') && e('1,3');
r($project2[2][1]) && p('project,product') && e('2,1');
r($project2[2][2]) && p('project,product') && e('2,2');