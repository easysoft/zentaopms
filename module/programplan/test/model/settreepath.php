#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');


function initData()
{
    zdTable('project')->config('project')->gen(10);
}

/**

title=测试 programplanModel->setTreePath();
cid=1
pid=1

*/

initData();

global $tester;
$tester->loadModel('programplan');

$tester->programplan->setTreePath(2);
$tester->programplan->setTreePath(9);


r($tester->programplan->getByID(2)) && p('path|grade', '|') && e(',1,2,| 1');   // 给stageID=2没有子阶段不走if设置path,grade
r($tester->programplan->getByID(9)) && p('path|grade', '|') && e(',7,8,9,| 3'); // 给stageID=9有子阶段走if设置path,grade

