#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->moveSprintStoryToProject()。
cid=1

- 检查所有的执行是否都被转移到了对应的项目中。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('projectstory')->gen(10);

$upgrade = new upgradeTest();

$projectIDList = array(1, 2, 3);
$sprintIDList  = array(array(11, 12), array(13, 14), array(15));
$storyIDList   = array(array(2, 4, 6, 8), array(10, 12, 14, 16), array(18, 20));

$upgrade->moveSprintStoryToProject($projectIDList[0], $sprintIDList[0]);
$upgrade->moveSprintStoryToProject($projectIDList[1], $sprintIDList[1]);
$upgrade->moveSprintStoryToProject($projectIDList[2], $sprintIDList[2]);

$check = true;
foreach($storyIDList as $key => $storyID)
{
    $relations = $tester->dao->select('*')->from(TABLE_PROJECTSTORY)->where('story')->in($storyID)->andWhere('project')->eq($projectIDList[$key])->fetchAll();
    if(count($relations) !== count($storyID)) $check = false;
}

r($check) && p('') && e(1);  //检查所有的执行是否都被转移到了对应的项目中。
