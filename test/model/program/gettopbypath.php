#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getTopByPath();
cid=1
pid=1

获取顶级项目集的顶级id     >> 1
获取子项目集的顶级父项目集 >> 2

*/

global $tester;
$tester->loadModel('program');

r($tester->program->getTopByPath(',1,'))   && p() && e('1'); // 获取顶级项目集的顶级id
r($tester->program->getTopByPath(',2,3,')) && p() && e('2'); // 获取子项目集的顶级父项目集
