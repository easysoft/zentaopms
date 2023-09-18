#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->gen(50);
zdTable('team')->gen(0);

$task = zdTable('task');
$task->project->range('1-20');
$task->gen(100);

/**

title=测试 programModel::appendStatToProjects();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');

$projectID = 11;
$hour      = new stdclass();
$hour->totalConsumed = 15;
$hour->totalEstimate = 30;
$hour->totalLeft     = 10;
$hour->progress      = 60;
$hours[$projectID]   = $hour;

$projects = $tester->program->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchAll('id');
$teams    = $tester->program->dao->select('t1.root,t1.account')->from(TABLE_TEAM)->alias('t1')
    ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
    ->where('t1.root')->eq($projectID)
    ->andWhere('t1.type')->eq('project')
    ->andWhere('t2.deleted')->eq(0)
    ->fetchGroup('root', 'account');
$leftTasks = $tester->program->dao->select('t2.parent as project, count(*) as tasks')->from(TABLE_TASK)->alias('t1')
    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
    ->where('t1.project')->eq($projectID)
    ->andWhere('t1.status')->notIn('cancel,closed')
    ->groupBy('t2.parent')
    ->fetchAll('project');

r($tester->program->appendStatToProjects(array()))   && p()        && e('0');  // 不传入任何数据
r($tester->program->appendStatToProjects($projects)) && p('11:id') && e('11'); // 只传入项目数据

$stats = $tester->program->appendStatToProjects($projects, 'hours');
r(isset($stats[$projectID]->hours))                                        && p() && e('0'); // 只传入项目数据和hours追加字段
$stats = $tester->program->appendStatToProjects($projects, 'teamCount');
r(isset($stats[$projectID]->teamCount))                                    && p() && e('1'); // 只传入项目数据和teamCount追加字段
$stats = $tester->program->appendStatToProjects($projects, 'teamMembers');
r(isset($stats[$projectID]->teamMembers))                                  && p() && e('0'); // 只传入项目数据和teamMembers追加字段
$stats = $tester->program->appendStatToProjects($projects, 'leftTasks');
r(isset($stats[$projectID]->leftTasks))                                    && p() && e('0'); // 只传入项目数据和leftTasks追加字段

$stats = $tester->program->appendStatToProjects($projects, 'hours,teamCount,teamMembers,leftTasks', array('hours' => $hours, 'teams' => $teams, 'leftTasks' => $leftTasks));
$stat  = $stats[$projectID];
r(isset($stat->hours))       && p() && e('1'); //正常传入数据，检查hours字段
r(isset($stat->teamCount))   && p() && e('1'); //正常传入数据，检查teamCount字段
r(isset($stat->teamMembers)) && p() && e('1'); //正常传入数据，检查teamMembers字段
r(isset($stat->leftTasks))   && p() && e('1'); //正常传入数据，检查leftTasks字段
