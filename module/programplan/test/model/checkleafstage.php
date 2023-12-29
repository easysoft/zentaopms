#!/usr/bin/env php
<?php

/**

title=测试programplanModel->checkLeafStage();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('checkleafstage')->gen(5);

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->checkLeafStage($stageID = 0)) && p('') && e('0'); // 获取阶段ID为0，判断是是否为叶子节点，结果为0
r($tester->programplan->checkLeafStage($stageID = 2)) && p('') && e('0'); // 获取阶段ID为2，判断是是否为叶子节点，结果为0
r($tester->programplan->checkLeafStage($stageID = 5)) && p('') && e('1'); // 获取阶段ID为5，判断是否为叶子节点，结果为1
