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
$project4 = $tester->project->getProgramMaxEnd(1);
$project5 = $tester->project->getProgramMaxEnd(3);
$project6 = $tester->project->getProgramMaxEnd(6);

r($project6) && p('maxEnd')   && e('2023-06-12'); //获取一级项目集最大结束时间:尾级项目结束时间最大
r($project5) && p('maxEnd')   && e('2023-07-03'); //获取二级项目集最大结束时间:顶级项目结束时间最大
r($project4) && p('maxEnd')   && e('2023-05-08'); //获取三级项目集最大结束时间:开始时间等于结束时间
