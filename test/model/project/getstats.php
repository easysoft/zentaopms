#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getStats();
cid=1
pid=1

查看所有执行 >> 630
查看未完成的执行 >> 0
查看所有进行中的执行 >> 315
查看所有进行中的执行 >> 315
查看id为11项目的执行 >> 7
查看id为12项目的执行 >> 7

*/

$t = new Project('admin');

$OpenImplement = array('all', 'undone', 'doing', 'wait', '11', '12');

r($t->getByStatusExe($OpenImplement[0])) && p() && e('630');   //查看所有执行
r($t->getByStatusExe($OpenImplement[1])) && p() && e('0');   //查看未完成的执行
r($t->getByStatusExe($OpenImplement[2])) && p() && e('315'); //查看所有进行中的执行
r($t->getByStatusExe($OpenImplement[3])) && p() && e('315'); //查看所有进行中的执行
r($t->getByProject($OpenImplement[4]))   && p() && e('7');   //查看id为11项目的执行
r($t->getByProject($OpenImplement[5]))   && p() && e('7');   //查看id为12项目的执行