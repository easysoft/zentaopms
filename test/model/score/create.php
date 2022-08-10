#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/score.class.php';
su('admin');

/**

title=测试 scoreModel->create();
cid=1
pid=1

关闭一个不存在创建者的需求 >> 0
关闭一个存在创建者的需求 >> 2
ID为601 的任务有子任务，完成不计算积分 >> 0
ID为2 的任务,优先级为2，消耗4h, 预计1h >> 2
确认严重程度为1的bug >> 4
在截止时间内关闭一个项目经理为admin的执行 >> 30
product模块的edit方法不存在与积分规则中，不计算积分 >> 0

*/

$moduleList  = array('user', 'story', 'task', 'bug', 'execution', 'product');
$methodList  = array('login', 'close', 'finish', 'confirmBug', 'close', 'edit');
$accountList = array('admin', 'dev10', 'test10', 'top10');

$bug           = new stdclass();
$bug->id       = '1';
$bug->openedBy = 'admin';
$bug->severity = '1';

$execution      = new stdclass();
$execution->id  = '701';
$execution->PM  = 'admin';
$execution->end = '2022-05-14';

$score = new scoreTest();

r($score->createTest($moduleList[1], $methodList[1], '402'))                             && p('')      && e('0');     // 关闭一个不存在创建者的需求
r($score->createTest($moduleList[1], $methodList[1], '9'))                               && p('score') && e('2');     // 关闭一个存在创建者的需求
r($score->createTest($moduleList[2], $methodList[2], '601'))                             && p('')      && e('0');     // ID为601 的任务有子任务，完成不计算积分
r($score->createTest($moduleList[2], $methodList[2], '2'))                               && p('score') && e('2');     // ID为2 的任务,优先级为2，消耗4h, 预计1h
r($score->createTest($moduleList[3], $methodList[3], $bug))                              && p('score') && e('4');     // 确认严重程度为1的bug
r($score->createTest($moduleList[4], $methodList[4], $execution, 'admin', '2022-05-11')) && p('score') && e('30');    // 在截止时间内关闭一个项目经理为admin的执行
r($score->createTest($moduleList[5], $methodList[5]))                                    && p('')      && e('0');     // product模块的edit方法不存在与积分规则中，不计算积分