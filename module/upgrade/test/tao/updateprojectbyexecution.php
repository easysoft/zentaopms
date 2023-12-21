#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateProjectByExecution()。
cid=1

- 测试执行id为1和2的执行相关的数据，是否都转移到了项目id为1的项目中。@1
- 测试执行id为3和4的执行相关的数据，是否都转移到了项目id为2的项目中。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

$types = array('bug', 'build', 'doc', 'doclib', 'effort', 'task', 'testtask', 'effort');

foreach ($types as $type) zdTable($type)->config($type)->gen(4);

$projectIDList = array(1, 2);
$productIDList = array(array(1, 2), array(3, 4));

$upgrade->updateProjectByExecution($projectIDList[0], $productIDList[0]);

function checkResult($projectID)
{
    global $tester;
    $tasks       = $tester->dao->select('*')->from(TABLE_TASK)->where('project')->eq($projectID)->fetchAll();
    $builds      = $tester->dao->select('*')->from(TABLE_BUILD)->where('project')->eq($projectID)->fetchAll();
    $bugs        = $tester->dao->select('*')->from(TABLE_BUG)->where('project')->eq($projectID)->fetchAll();
    $docs        = $tester->dao->select('*')->from(TABLE_DOC)->where('project')->eq($projectID)->fetchAll();
    $doclibs     = $tester->dao->select('*')->from(TABLE_DOCLIB)->where('project')->eq($projectID)->fetchAll();
    $testtasks   = $tester->dao->select('*')->from(TABLE_TESTTASK)->where('project')->eq($projectID)->fetchAll();
    $efforts     = $tester->dao->select('*')->from(TABLE_EFFORT)->where('project')->eq($projectID)->fetchAll();

    if(count($tasks) !== 2) return false;
    if(array_filter($tasks, function($task)use($projectID){return $task->project != $projectID;})) return false;

    if(count($builds) !== 2) return false;
    if(array_filter($builds, function($build)use($projectID){return $build->project != $projectID;})) return false;

    if(count($bugs) !== 2) return false;
    if(array_filter($bugs, function($bug)use($projectID){return $bug->project != $projectID;})) return false;

    if(count($docs) !== 2) return false;
    if(array_filter($docs, function($doc)use($projectID){return $doc->project != $projectID;})) return false;

    if(count($doclibs) !== 2) return false;
    if(array_filter($doclibs, function($doclib)use($projectID){return $doclib->project != $projectID;})) return false;

    if(count($testtasks) !== 2) return false;
    if(array_filter($testtasks, function($testtask)use($projectID){return $testtask->project != $projectID;})) return false;

    if(count($efforts) !== 2) return false;
    if(array_filter($efforts, function($effort)use($projectID){return $effort->project != $projectID;})) return false;

    return true;
}

r(checkResult(1)) && p('') && e(1);  //测试执行id为1和2的执行相关的数据，是否都转移到了项目id为1的项目中。

$upgrade->updateProjectByExecution($projectIDList[1], $productIDList[1]);

r(checkResult(2)) && p('') && e(1);  //测试执行id为3和4的执行相关的数据，是否都转移到了项目id为2的项目中。
