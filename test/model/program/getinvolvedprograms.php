#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getInvolvedPrograms();
cid=1
pid=1

获取admin参与的项目集数量 >> 2
获取test2参与的项目集数量 >> 1
获取admin参与的项目集ID >> 10
获取test2参与的项目集ID >> 1

*/

global $tester;
$tester->loadModel('program');
$adminPrograms = $tester->program->getInvolvedPrograms('admin');
$test2Programs = $tester->program->getInvolvedPrograms('test2');

r(count($adminPrograms)) && p()     && e('2');  // 获取admin参与的项目集数量
r(count($test2Programs)) && p()     && e('1');  // 获取test2参与的项目集数量
r($adminPrograms)        && p('10') && e('10'); // 获取admin参与的项目集ID
r($test2Programs)        && p('1')  && e('1');  // 获取test2参与的项目集ID