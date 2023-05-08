#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zdTable('project')->config('project')->gen(6);

/**

title=测试 projectModel->getProgramMaxEnd();
cid=1
pid=1
*/

global $tester;

$tester->loadModel('project');
$project1 = $tester->project->getProgramMaxEnd(1);
$project2 = $tester->project->getProgramMaxEnd(3);
$project3 = $tester->project->getProgramMaxEnd(6);

a($project1);die;
r($project1) && p() && e(); //获取一级项目集最小开始时间
r($project1) && p() && e(); //获取一级项目集最大结束时间
r($project2) && p() && e(); //获取二级项目集最小开始时间
r($project2) && p() && e(); //获取二级项目集最大结束时间
r($project3) && p() && e(); //获取三级项目集最小开始时间
r($project3) && p() && e(); //获取三级项目集最大结束时间
