#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project')->loadYaml('project')->gen(11);

/**

title=测试 projectModel->getProgramMinBegin();
timeout=0
cid=1

*/

global $tester;

$tester->loadModel('project');
$project1 = $tester->project->getProgramMinBegin(1);
$project2 = $tester->project->getProgramMinBegin(4);
$project3 = $tester->project->getProgramMinBegin(8);

r($project1) && p('maxBegin')   && e('2023-05-07'); //获取二级项目集下唯一项目最小开始时间
r($project2) && p('maxBegin')   && e('2023-04-22'); //获取一级项目集下项目最小开始时间
r($project3) && p('maxBegin')   && e('2023-05-01'); //获取二级项目集下多个项目最小开始时间
