#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getStats4Kanban();
cid=1
pid=1

统计获取到的看板数据的分组数量 >> 2
统计获取到的看板数据的其他数量 >> 11
统计获取到的看板数据的其他下的项目集1下的wait的项目数量 >> 3
统计获取到的看板数据的其他下的项目集1下的doing的项目数量 >> 4

*/

global $tester;
$tester->loadModel('project');
$kanbanGroup = $tester->project->getStats4Kanban();

r(count($kanbanGroup['kanbanGroup']))                      && p() && e('2');  //统计获取到的看板数据的分组数量
r(count($kanbanGroup['kanbanGroup']['other']))             && p() && e('11'); //统计获取到的看板数据的其他数量
r(count($kanbanGroup['kanbanGroup']['other'][1]['wait']))  && p() && e('3');  //统计获取到的看板数据的其他下的项目集1下的wait的项目数量
r(count($kanbanGroup['kanbanGroup']['other'][1]['doing'])) && p() && e('4');  //统计获取到的看板数据的其他下的项目集1下的doing的项目数量