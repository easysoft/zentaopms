#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->gen('50');
su('admin');

/**

title=测试 projectModel->getPairsByModel();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getPairsByModel('all'))           && p('26') && e('项目集6 / 项目26'); // 获取所有项目
r($tester->project->getPairsByModel('scrum'))         && p('21') && e('项目集1 / 项目21'); // 获取敏捷项目
r($tester->project->getPairsByModel('waterfallplus')) && p('37') && e('项目集7 / 项目37'); // 获取融合瀑布类型项目

r($tester->project->getPairsByModel('all', 'noclosed'))          && p('43') && e('项目集3 / 项目43'); // 获取未关闭的项目
r($tester->project->getPairsByModel('all', 'multiple'))          && p('33') && e('项目集3 / 项目33'); // 获取启用迭代的项目
r($tester->project->getPairsByModel('all', 'noclosed,multiple')) && p('43') && e('项目集3 / 项目43'); // 获取未关闭的启用迭代的项目

r($tester->project->getPairsByModel('all', 'noclosed', 50))                && p('21') && e('项目集1 / 项目21'); // 获取所有项目
r($tester->project->getPairsByModel('waterfall', 'noclosed', 11))          && p('22') && e('项目集2 / 项目22'); // 获取所有瀑布项目
r($tester->project->getPairsByModel('agileplus', 'noclosed,multiple', 11)) && p('34') && e('项目集4 / 项目34'); // 获取所有瀑布项目
