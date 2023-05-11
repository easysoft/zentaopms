#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
   zdTable('project')->config('project')->gen(10);
}

/**

title=测试 programplanModel->getByID();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->getByID(2)) && p('name,code') && e('执行1-1,sprint1-1'); // 判断项目阶段id=2的name
r($tester->programplan->getByID(1)) && p('milestone,setMilestone') && e('0,~~'); // 判断项目id=1走else的milestone,setMilestone
