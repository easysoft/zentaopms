#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

function initData()
{
    zdTable('project')->config('project')->gen(13);
    zdTable('task')->config('task')->gen(13);
}

/**

title=测试 programplanModel->computeProgress();
timeout=0
cid=1

*/

initData();

global $tester;
$programplan = new programplanTest();
$tester->loadModel('programplan')->programplanTao;

$project = $programplan->getByIdTest(2);
$result  = $tester->programplan->getNewParentAndAction(['wait' => 1], $project, 0, 'edit');
$parent1 = $result['newParent'];


$project = $programplan->getByIdTest(5);
$result  = $tester->programplan->getNewParentAndAction(['closed' => 1], $project, 0, 'edit');
$parent2 = $result['newParent'];

$project = $programplan->getByIdTest(8);
$result  = $tester->programplan->getNewParentAndAction(['suspended' => 1], $project, 0, 'edit');
$parent3 = $result['newParent'];

$project = $programplan->getByIdTest(11);
$result  = $tester->programplan->getNewParentAndAction(['wait' => 2, 'closed' => 1], $project, 0, 'edit');
$parent4 = $result['newParent'];


r($parent1)        && p('status') && e('wait');      // 返回状态为wait
r($parent2)        && p('status') && e('closed');    // 返回状态为closed
r($parent3)        && p('status') && e('suspended'); // 返回状态为suspended
r($parent4)        && p('status') && e('doing');     // 返回状态为doing
