#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getOverviewList;
cid=1
pid=1

获取未开始的项目数量 >> 15
获取状态不为done和closed的项目数量 >> 15
根据项目ID获取项目 >> 1
获取不存在的项目 >> 0
按照ID正序获取项目列表,查看排第一个的项目详情 >> 11,项目1
按照项目名称倒序获取项目列表,查看排第一个的项目详情 >> 100,项目90

*/

global $tester;
$tester->loadModel('project');

$byStatus1 = $tester->project->getOverviewList('byStatus', 'wait');
$byStatus2 = $tester->project->getOverviewList('byStatus', 'undone');

$byId1 = $tester->project->getOverviewList('byid', '11');
$byId2 = $tester->project->getOverviewList('byid', '10000');

$byOrder1 = $tester->project->getOverviewList('byStatus', 'all', 'id_asc');
$byOrder2 = $tester->project->getOverviewList('byStatus', 'all', 'name_desc');

r(count($byStatus1))  && p()          && e('15');         // 获取未开始的项目数量
r(count($byStatus2))  && p()          && e('15');         // 获取状态不为done和closed的项目数量
r(count($byId1))      && p()          && e('1');          // 根据项目ID获取项目
r(count($byId2))      && p()          && e('0');          // 获取不存在的项目
r(current($byOrder1)) && p('id,name') && e('11,项目1');   // 按照ID正序获取项目列表,查看排第一个的项目详情
r(current($byOrder2)) && p('id,name') && e('100,项目90'); // 按照项目名称倒序获取项目列表,查看排第一个的项目详情