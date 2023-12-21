#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->moveAllTestTaskToProject()。
cid=1

- 检查所有的执行是否都被转移到了对应的项目中。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('projectcase')->gen(0);
zdTable('testtask')->gen(3);
zdTable('testrun')->gen(10);

$upgrade = new upgradeTest();

$projectIDList = array(1, 2, 3);
$sprintIDList  = array(array(101), array(102), array(103));
$caseIDList   = array(array(1, 2, 3, 4), array(5, 6, 7, 8), array(9, 10));

$upgrade->moveAllTestTaskToProject($projectIDList[0], $sprintIDList[0]);
$upgrade->moveAllTestTaskToProject($projectIDList[1], $sprintIDList[1]);
$upgrade->moveAllTestTaskToProject($projectIDList[2], $sprintIDList[2]);

$check = true;
foreach($caseIDList as $key => $caseID)
{
    $relations = $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('case')->in($caseID)->andWhere('project')->eq($projectIDList[$key])->fetchAll();
    if(count($relations) !== count($caseID)) $check = false;
}

r($check) && p('') && e(1);  //检查所有的测试任务和相关用例是否都被转移到了对应的项目中。
