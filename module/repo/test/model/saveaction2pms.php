#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->saveAction2PMS();
timeout=0
cid=18093

- 开始任务
 - 第1条的status属性 @doing
 - 第1条的consumed属性 @4.00
 - 第1条的left属性 @3.00
- 完成任务
 - 第2条的status属性 @done
 - 第2条的consumed属性 @14.00
 - 第2条的left属性 @0.00
- 工时计算
 - 第8条的status属性 @doing
 - 第8条的consumed属性 @11.00
 - 第8条的left属性 @3.00
- 修复bug1
 - 第1条的status属性 @resolved
 - 第1条的resolution属性 @fixed
- 修复bug2
 - 第2条的status属性 @resolved
 - 第2条的resolution属性 @fixed

*/
zenData('project')->gen(0);
zenData('task')->gen(0);
zenData('bug')->gen(0);
zenData('effort')->gen(0);
zenData('action')->gen(0);
zenData('history')->gen(0);

$project = zenData('project');
$project->id->range('1-3');
$project->project->range('0,0,2');
$project->name->range('项目集1,项目1,迭代1');
$project->code->range('program1,project1,sprint1');
$project->type->range('program,project,sprint');
$project->model->range('scrum{3}');
$project->status->range('doing{3}');
$project->parent->range('0,1,2');
$project->grade->range('1-3');
$project->begin->range('`2026-07-09`{3}');
$project->end->range('`2026-07-16`{3}');
$project->openedBy->range('admin{3}');
$project->openedDate->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$project->acl->range('open{3}');
$project->multiple->range('1{3}');
$project->vision->range('rnd{3}');
$project->deleted->range('0{3}');
$project->gen(3);

$task = zenData('task');
$task->id->range('1,2,8');
$task->parent->range('0{3}');
$task->project->range('2{3}');
$task->execution->range('3{3}');
$task->module->range('21,24,42');
$task->story->range('1,5,29');
$task->name->range('开发任务11,开发任务12,开发任务18');
$task->type->range('design,devel,misc');
$task->pri->range('1,2,4');
$task->estimate->range('0,1,7');
$task->consumed->range('3,4,10');
$task->left->range('0,1,4');
$task->deadline->range('`2026-07-16`,`2026-07-15`,`2026-07-09`');
$task->status->range('wait,doing,doing');
$task->desc->range('这里是任务描述1,这里是任务描述2,这里是任务描述8');
$task->version->range('1{3}');
$task->openedBy->range('admin{3}');
$task->openedDate->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$task->assignedDate->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$task->estStarted->range('`2026-07-09`{3}');
$task->realStarted->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$task->planDuration->range('1{3}');
$task->realDuration->range('1{3}');
$task->mode->range('none{3}');
$task->deleted->range('0{3}');
$task->gen(3);

$bug = zenData('bug');
$bug->id->range('1-2');
$bug->project->range('2{2}');
$bug->product->range('1{2}');
$bug->module->range('1821,1822');
$bug->execution->range('3{2}');
$bug->plan->range('1{2}');
$bug->story->range('2,6');
$bug->title->range('BUG1,BUG2');
$bug->severity->range('1,2');
$bug->pri->range('1,2');
$bug->type->range('codeerror,config');
$bug->steps->range('step1,step2');
$bug->status->range('active{2}');
$bug->color->range('#3da7f5,#75c941');
$bug->openedBy->range('admin{2}');
$bug->openedDate->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$bug->openedBuild->range('1{2}');
$bug->assignedTo->range('admin{2}');
$bug->assignedDate->range('20260709 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$bug->deadline->range('`2026-07-16`,`2026-07-15`');
$bug->deleted->range('0{2}');
$bug->gen(2);

global $app;
$app->rawModule = 'repo';
$app->rawMethod = 'browse';

include($app->getModuleRoot() . '/repo/control.php');
$app->control = new repo();

$repoID   = 1;
$repoRoot = '';
$scm      = 'gitlab';

$log = new stdclass();
$log->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log->committer = 'root';
$log->time      = '2023-12-29 10:44:36';
$log->comment   = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->author    = 'user4';
$log->msg       = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$repo = new repoModelTest();
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('1:status,consumed,left') && e('doing,4.00,3.00'); //开始任务
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('2:status,consumed,left') && e('done,14.00,0.00'); //完成任务
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('8:status,consumed,left') && e('doing,11.00,3.00');  //工时计算
$log->msg = $log->comment = 'Fix bug#1,2';
r($repo->saveAction2PMSBugListTest($log, $repoID, array(1, 2))) && p('1:status,resolution') && e('resolved,fixed'); //修复bug1
r($repo->saveAction2PMSBugListTest($log, $repoID, array(1, 2))) && p('2:status,resolution') && e('resolved,fixed'); //修复bug2
