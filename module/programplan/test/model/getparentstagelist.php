#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('project')->gen(10);
zdTable('projectproduct')->config('projectproduct')->gen(10);
zdTable('task')->gen(0);
su('admin');

/**

title=测试 programplanModel->getParentStageList();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->getParentStageList(1, 10, 2)[2]) && p() && e('执行1-1');   // 测试查询项目1产品2阶段10父阶段信息
r($tester->programplan->getParentStageList(1, 10, 3)[3]) && p() && e('执行1-1-1'); // 测试查询项目1产品3阶段10父阶段信息
r($tester->programplan->getParentStageList(4, 10, 6)[6]) && p() && e('执行2-1-1'); // 测试查询项目4产品6阶段10父阶段信息
