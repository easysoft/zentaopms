#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->computeExecutionScore();
cid=1

- 计算迭代ID=0时，关闭执行的分数第0条的score属性 @0
- 计算迭代ID=101时，关闭执行的分数第0条的score属性 @20
- 计算迭代ID不存在时，关闭执行的分数第0条的score属性 @0
- 计算迭代ID=0时，开始执行的分数 @0
- 计算迭代ID=101时，开始执行的分数 @0
- 计算迭代ID不存在时，开始执行的分数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
zdTable('project')->config('project')->gen(5);

$executionIds = array(0, 101, 110);
$methods      = array('close', 'start');

$scoreTester = new scoreTest();
r($scoreTester->computeExecutionScoreTest($executionIds[0], $methods[0])) && p('0:score') && e('0');  // 计算迭代ID=0时，关闭执行的分数
r($scoreTester->computeExecutionScoreTest($executionIds[1], $methods[0])) && p('0:score') && e('20'); // 计算迭代ID=101时，关闭执行的分数
r($scoreTester->computeExecutionScoreTest($executionIds[2], $methods[0])) && p('0:score') && e('0');  // 计算迭代ID不存在时，关闭执行的分数
r($scoreTester->computeExecutionScoreTest($executionIds[0], $methods[1])) && p()          && e('0');  // 计算迭代ID=0时，开始执行的分数
r($scoreTester->computeExecutionScoreTest($executionIds[1], $methods[1])) && p()          && e('0');  // 计算迭代ID=101时，开始执行的分数
r($scoreTester->computeExecutionScoreTest($executionIds[2], $methods[1])) && p()          && e('0');  // 计算迭代ID不存在时，开始执行的分数
