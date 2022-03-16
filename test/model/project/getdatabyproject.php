#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getDataByProject();
cid=1
pid=1

获取parent为11首个项目的id >> 129
获取parent为11的首个项目的id >> 130
获取parent为11的首个项目的id >> 131
获取parent为10000的首个项目的id >> 0
获取parent为131的首个版本的id >> 1
获取parent为132的首个版本的id >> 6
获取parent为10000的首个版本的id >> 0
获取parent为132的首个发布的id >> 1
获取parent为132的首个发布的id >> 6
获取parent为10000的首个发布的id >> 0

*/

/* 还有case bug testtask doc表要写，暂时表中没数据 */

$t = new Project('admin');

$PID = array(39, 40, 'sprint', 41, 'stage', 10000, 131, 132, 10000, 131, 132,  10000);

r($t->getExecutionData($PID[0]))          && p('id') && e('129'); //获取parent为11首个项目的id
r($t->getExecutionData($PID[1], $PID[2])) && p('id') && e('130'); //获取parent为11的首个项目的id
r($t->getExecutionData($PID[3], $PID[4])) && p('id') && e('131'); //获取parent为11的首个项目的id
r($t->getExecutionData($PID[5]))          && p('id') && e('0');   //获取parent为10000的首个项目的id
r($t->getBuildData($PID[6]))              && p('id') && e('1');   //获取parent为131的首个版本的id
r($t->getBuildData($PID[7]))              && p('id') && e('6');   //获取parent为132的首个版本的id
r($t->getBuildData($PID[8]))              && p('id') && e('0');   //获取parent为10000的首个版本的id
r($t->getReleaseData($PID[9]))            && p('id') && e('1');   //获取parent为132的首个发布的id
r($t->getReleaseData($PID[10]))           && p('id') && e('6');   //获取parent为132的首个发布的id
r($t->getReleaseData($PID[11]))           && p('id') && e('0');   //获取parent为10000的首个发布的id