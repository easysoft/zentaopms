#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->moveSprintCaseToProject()。
cid=1

- 检查所有的执行是否都被转移到了对应的项目中。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('projectcase')->gen(10);

$upgrade = new upgradeTest();

$projectIDList = array(1, 2, 3);
    $sprintIDList  = array(array(101), array(102), array(103));
$storyIDList   = array(array(1, 2, 3, 4), array(5, 6, 7, 8), array(9, 10));

$upgrade->moveSprintCaseToProject($projectIDList[0], $sprintIDList[0]);
$upgrade->moveSprintCaseToProject($projectIDList[1], $sprintIDList[1]);
$upgrade->moveSprintCaseToProject($projectIDList[2], $sprintIDList[2]);

$check = true;
foreach($storyIDList as $key => $storyID)
{
    $relations = $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('case')->in($storyID)->andWhere('project')->eq($projectIDList[$key])->fetchAll();
    if(count($relations) !== count($storyID)) $check = false;
}

r($check) && p('') && e(1);  //检查所有的用例是否都被转移到了对应的项目中。
