#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('project,stage,sprint,stage{2},sprint');
$execution->parent->range('0,1{3},2{2}');
$execution->status->range('wait');
$execution->gen(6);


$team = zdTable('team');
$team->root->range('3-5');
$team->account->range('1-5')->prefix('user');
$team->role->range('研发{3},测试{2}');
$team->type->range('execution');
$team->join->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$team->gen(5);

zdTable('user')->gen(5);
su('admin');

/**

title=测试executionModel->getTeams2ImportTest();
cid=1
pid=1

无效数据查询     >> 无数据
正常数据查询     >> 迭代3
正常数据查询统计 >> 1

*/

$executionID = '2';
$accountList = array('user10', 'user5');
$count       = array('0','1');

$execution = new executionTest();

r($execution->getTeams2ImportTest($accountList[0], $executionID, $count[0])) && p()    && e('无数据'); // 无效数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[0])) && p('4') && e('迭代3');  // 正常数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[1])) && p()    && e('1');      // 正常数据查询统计
