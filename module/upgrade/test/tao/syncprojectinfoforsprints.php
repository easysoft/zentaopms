#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->syncProjectInfoForSprints()。
cid=1

- 检查所有的执行是否都被转移到了对应的项目中。@1
- 检查项目和项目集的开始结束时间是否被成功更新。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('project')->config('project')->gen(5, false, false);

$upgrade = new upgradeTest();

$programID = 1;
$projectID = array(2, 3, 4, 5);
$sprintIDList = array(array(101, 102, 103, 104), array(105, 106, 107, 108), array(109, 110));

$oriSprintList = $tester->dao->select('project, parent, grade, path, acl')->from(TABLE_EXECUTION)->where('id')->in($sprintIDList[0])->fetchAll();
$upgrade->syncProjectInfoForSprints($projectID[0], $sprintIDList[0], $programID, false);

$checkSprint = true;
$sprintList = $tester->dao->select('id, project, parent, grade, path, acl')->from(TABLE_EXECUTION)->where('id')->in($sprintIDList[0])->fetchAll();

foreach($sprintList as $key => $sprint)
{
    if($sprint->project != $projectID[0])
    {
        $checkSprint = false;
        break;
    }
    if($sprint->parent != $projectID[0])
    {
        $checkSprint = false;
        break;
    }
    if($sprint->grade != 1)
    {
        $checkSprint = false;
        break;
    }
    if($sprint->path != ",{$projectID[0]},{$sprint->id},")
    {
        $checkSprint = false;
        break;
    }
    if($oriSprintList[$key]->acl == 'custom')
    {
        $checkSprint = $sprint->acl == 'private';
        break;
    }
}

r($checkSprint) && p('') && e(1);  //检查所有的执行是否都被转移到了对应的项目中。

zdTable('action')->config('action')->gen(10);
$checkSprint = true;
$oriSprintList = $tester->dao->select('project, parent, grade, path, acl, begin, end')->from(TABLE_EXECUTION)->where('id')->in($sprintIDList[1])->fetchAll();
$upgrade->syncProjectInfoForSprints($projectID[1], $sprintIDList[1], $programID, true);
list($minRealBegin, $maxRealEnd) = $upgrade->getMaxAndMinDateByAction($projectID[1], $sprintIDList[1]);

$startTimeArr = array_map('strtotime', array_map(function($sprint) {return $sprint->begin;}, $oriSprintList));
$endTimeArr   = array_map('strtotime', array_map(function($sprint) {return $sprint->end;}, $oriSprintList));
$maxStartTime = max($startTimeArr);
$minEndTime   = min($endTimeArr);

unset(dao::$cache[TABLE_PROJECT]);
$project = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID[1])->fetch();
$program = $tester->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
if(strtotime($project->begin) === $maxStartTime) $checkSprint = false;
if(strtotime($project->end) === $minEndTime) $checkSprint = false;
if(strtotime($program->begin) === $maxStartTime) $checkSprint = false;
if(strtotime($program->end) === $minEndTime) $checkSprint = false;
if($project->realBegan != substr($minRealBegin, 0, 10)) $checkSprint = false;
if($project->realEnd != substr($maxRealEnd, 0, 10)) $checkSprint = false;
if($project->closedDate != $maxRealEnd) $checkSprint = false;

r($checkSprint) && p('') && e(1);  //检查项目和项目集的开始结束时间是否被成功更新。
