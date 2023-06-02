#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$release = zdTable('release');
$release->stories->range('2');
$release->branch->range('0,1,2');
$release->createdBy->range('admin');
$release->gen(3);

/**

title=测试 storyModel->computeStagesByTasks();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->branches = array(0);

$taskStat = array();
$taskStat[0][0]['devel'] = array('wait' => 3, 'doing' => 0, 'done' => 0, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 0, 'doing' => 0, 'done' => 0, 'pause' => 0);
$taskStat[1][0] = 3;
$taskStat[2][0] = 0;

r($storyModel->computeStagesByTasks(0, array()))                                         && p() && e('0');            //不传入任何数据。
r($storyModel->computeStagesByTasks(1, array()))                                         && p() && e('0');            //只传入需求 ID。
r($storyModel->computeStagesByTasks(0, array(), array(0 => 'planned')))                  && p('0') && e('planned');   //只传入 stages。
r($storyModel->computeStagesByTasks(1, array(), array(0 => 'planned'), $linkedProjects)) && p('0') && e('projected'); //传入 stages 和 linkedProjects。

r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('projected'); //传入未开始的任务状态统计。

$taskStat[0][0]['devel'] = array('wait' => 2, 'doing' => 1, 'done' => 0, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developing'); //没有测试任务，传入进行中的任务状态统计。
$taskStat[0][0]['devel'] = array('wait' => 2, 'doing' => 0, 'done' => 1, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developing'); //没有测试任务，传入没有全部完成的任务状态统计。
$taskStat[0][0]['devel'] = array('wait' => 1, 'doing' => 0, 'done' => 1, 'pause' => 1);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developing'); //没有测试任务，传入包含暂停的任务状态统计。
$taskStat[0][0]['devel'] = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developed');  //没有测试任务，传入全部完成的任务状态统计。

$taskStat[2][0] = 3;
$taskStat[0][0]['devel'] = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 3, 'doing' => 0, 'done' => 0, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developed');  //传入全部完成的研发任务，和未开始的测试任务。
$taskStat[0][0]['devel'] = array('wait' => 2, 'doing' => 1, 'done' => 0, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 3, 'doing' => 0, 'done' => 0, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('developing'); //传入包含进行中的研发任务，和未开始的测试任务。
$taskStat[0][0]['devel'] = array('wait' => 2, 'doing' => 0, 'done' => 1, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 2, 'doing' => 1, 'done' => 0, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('testing');    //传入包含进行中的研发任务，和包含进行中的测试任务。
$taskStat[0][0]['devel'] = array('wait' => 0, 'doing' => 0, 'done' => 2, 'pause' => 1);
$taskStat[0][0]['test']  = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('testing');    //传入包含已暂停的研发任务，和已经完成的测试任务。
$taskStat[0][0]['devel'] = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('tested');     //传入已经完成的研发任务，和已经完成的测试任务。
$taskStat[0][0]['devel'] = array('wait' => 0, 'doing' => 0, 'done' => 3, 'pause' => 0);
$taskStat[0][0]['test']  = array('wait' => 1, 'doing' => 0, 'done' => 2, 'pause' => 0);
r($storyModel->computeStagesByTasks(1, $taskStat, array())) && p('0') && e('testing');    //传入已经完成的研发任务，和未完成的测试任务。

r($storyModel->computeStagesByTasks(2, $taskStat, array())) && p('0,1,2') && e('released,released,released'); //已经发布的需求。
