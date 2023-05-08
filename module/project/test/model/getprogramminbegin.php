#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zdTable('project')->config('project')->gen(6);

/**

title=测试 projectModel->getProgramMinBegin();
cid=1
pid=1
*/

global $tester;

$tester->loadModel('project');
$project1 = $tester->project->getProgramMinBegin(1);
$project2 = $tester->project->getProgramMinBegin(3);
$project3 = $tester->project->getProgramMinBegin(6);

r($project1) && p('minBegin') && e('2023-05-01'); //获取一级项目集最小开始时间:顶级项目开始时间最小
r($project2) && p('minBegin') && e('2023-04-22'); //获取二级项目集最小开始时间:次级项目开始时间最小
r($project3) && p('minBegin') && e('2023-05-08'); //获取三级项目集最小开始时间:开始时间等于结束时间
