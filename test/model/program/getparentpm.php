#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getParentPM();
cid=1
pid=1

获取父项目集的负责人数量 >> 2
获取父项目集的负责人account >> pm2
获取父项目集的负责人account >> pm3

*/

global $tester;
$tester->loadModel('program');

$programIdList = array(11, 12, 13);
$parentPM = $tester->program->getParentPM($programIdList);

r(count($parentPM)) && p()         && e('2');   // 获取父项目集的负责人数量
r($parentPM)        && p('12:pm2') && e('pm2'); // 获取父项目集的负责人account
r($parentPM)        && p('13:pm3') && e('pm3'); // 获取父项目集的负责人account