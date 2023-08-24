#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 programModel::getTopByPath();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');

r($tester->program->getTopByPath(',1,'))   && p() && e('1'); // 获取顶级项目集的顶级id
r($tester->program->getTopByPath(',2,3,')) && p() && e('2'); // 获取子项目集的顶级父项目集
