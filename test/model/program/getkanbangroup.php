#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getKanbanGroup();
cid=1
pid=1

查看当前用户负责的项目集看板数量 >> 2
查看当前用户其他的项目集看板数量 >> 8
查看当前用户其他的项目集看板详情 >> 项目集2

*/

global $tester;
$tester->loadModel('program');

$kanbanGroup = $tester->program->getKanbanGroup();

r(count($kanbanGroup['my']))     && p('')       && e('2');       //查看当前用户负责的项目集看板数量
r(count($kanbanGroup['others'])) && p('')       && e('8');       //查看当前用户其他的项目集看板数量
r($kanbanGroup['others'])        && p('0:name') && e('项目集2'); //查看当前用户其他的项目集看板详情