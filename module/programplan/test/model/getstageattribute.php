#!/usr/bin/env php
<?php
/**

title=测试 programplanModel->getStageAttribute();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->getStageAttribute(0))   && p() && e('0');       // 验证id为0的阶段属性
r($tester->programplan->getStageAttribute(2))   && p() && e('review');  // 验证id为2的阶段属性
r($tester->programplan->getStageAttribute(3))   && p() && e('release'); // 验证id为3的阶段属性
r($tester->programplan->getStageAttribute(100)) && p() && e('0');       // 验证不存在的id 100 阶段属性
