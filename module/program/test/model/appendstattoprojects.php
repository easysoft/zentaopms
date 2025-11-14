#!/usr/bin/env php
<?php
/**

title=测试 programModel::appendStatToProjects();
timeout=0
cid=17671

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('user')->gen(5);
su('admin');

global $tester;
$tester->loadModel('program');

$projectID = 11;

$projects = $tester->program->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchAll('id');
$teams    = $tester->program->dao->select('t1.root,t1.account')->from(TABLE_TEAM)->alias('t1')
    ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
    ->where('t1.root')->eq($projectID)
    ->andWhere('t1.type')->eq('project')
    ->andWhere('t2.deleted')->eq(0)
    ->fetchGroup('root', 'account');

$stats[] = $tester->program->appendStatToProjects($projects);
$stats[] = $tester->program->appendStatToProjects($projects, $teams);
r($stats[0]) && p('11:estimate,left,consumed,teamCount,progress') && e('0,0,0,4,0.00'); // 测试不传入团队数据
r($stats[1]) && p('11:estimate,left,consumed,teamCount,progress') && e('0,0,0,4,0.00'); // 测试传入团队数据
