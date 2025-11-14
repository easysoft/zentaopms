#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(500);
zenData('task')->gen(1);
zenData('story')->gen(1);
zenData('bug')->gen(1);
zenData('testtask')->gen(1);
zenData('case')->gen(1);

/**

title=测试 commonModel::diff();
timeout=0
cid=15649

- 查看需求是否可以被修改 @1
- 查看bug是否可以被修改 @1
- 查看测试任务是否可以被修改 @1
- 查看任务是否可以被修改 @1
- 查看用例是否可以被修改 @1

*/

global $tester;

$task  = $tester->loadModel('task')->fetchById(1);
$story = $tester->loadModel('story')->fetchById(1);
$bug   = $tester->loadModel('bug')->fetchById(1);
$test  = $tester->loadModel('testtask')->fetchById(1);
$case  = $tester->loadModel('testcase')->fetchById(1);

$result1 = commonModel::canBeChanged('story', $story);
$result2 = commonModel::canBeChanged('bug', $bug);
$result3 = commonModel::canBeChanged('testtask', $test);
$result4 = commonModel::canBeChanged('task', $task);
$result5 = commonModel::canBeChanged('case', $case);

r($result1) && p() && e('1'); // 查看需求是否可以被修改
r($result2) && p() && e('1'); // 查看bug是否可以被修改
r($result3) && p() && e('1'); // 查看测试任务是否可以被修改
r($result4) && p() && e('1'); // 查看任务是否可以被修改
r($result5) && p() && e('1'); // 查看用例是否可以被修改