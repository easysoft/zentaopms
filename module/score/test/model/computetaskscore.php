#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->computeTaskScore();
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
zdTable('task')->gen(10);

$taskIds = array(0, 1, 7, 11);
$methods = array('finish', 'close');

$scoreTester = new scoreTest();
r($scoreTester->computeTaskScoreTest($taskIds[0], $methods[0])) && p()          && e('0'); // 测试空数据
r($scoreTester->computeTaskScoreTest($taskIds[1], $methods[0])) && p('1:score') && e('3'); // 计算完成普通任务的积分
r($scoreTester->computeTaskScoreTest($taskIds[2], $methods[0])) && p('1:score') && e('2'); // 计算完成子任务的积分
r($scoreTester->computeTaskScoreTest($taskIds[3], $methods[0])) && p()          && e('0'); // 计算完成任务ID不存在的积分
r($scoreTester->computeTaskScoreTest($taskIds[0], $methods[1])) && p()          && e('1'); // 计算关闭任务ID=0的积分
r($scoreTester->computeTaskScoreTest($taskIds[1], $methods[1])) && p()          && e('1'); // 计算关闭任务ID=1的积分
r($scoreTester->computeTaskScoreTest($taskIds[2], $methods[1])) && p()          && e('1'); // 计算关闭任务ID=7的积分
r($scoreTester->computeTaskScoreTest($taskIds[3], $methods[1])) && p()          && e('1'); // 计算关闭任务ID不存在的积分
