#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->gen(50);
zenData('userview')->gen(0);
su('admin');

/**

title=测试 projectModel->getPairsByModel();
timeout=0
cid=17839

- 获取所有项目属性26 @项目集6 / 项目26
- 获取敏捷项目属性21 @项目集1 / 项目21
- 获取融合瀑布类型项目属性37 @项目集7 / 项目37
- 获取未关闭的项目属性43 @项目集3 / 项目43
- 获取启用迭代的项目属性33 @项目集3 / 项目33
- 获取未关闭的启用迭代的项目属性43 @项目集3 / 项目43
- 获取所有项目属性50 @项目集10 / 项目50
- 获取所有未关闭的瀑布项目属性12 @项目集2 / 项目12
- 获取所有未关闭的融合瀑布项目属性11 @项目集1 / 项目11

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getPairsByModel('all'))           && p('26') && e('项目集6 / 项目26'); // 获取所有项目
r($tester->project->getPairsByModel('scrum'))         && p('21') && e('项目集1 / 项目21'); // 获取敏捷项目
r($tester->project->getPairsByModel('waterfallplus')) && p('37') && e('项目集7 / 项目37'); // 获取融合瀑布类型项目

r($tester->project->getPairsByModel('all', 'noclosed'))          && p('43') && e('项目集3 / 项目43'); // 获取未关闭的项目
r($tester->project->getPairsByModel('all', 'multiple'))          && p('33') && e('项目集3 / 项目33'); // 获取启用迭代的项目
r($tester->project->getPairsByModel('all', 'noclosed,multiple')) && p('43') && e('项目集3 / 项目43'); // 获取未关闭的启用迭代的项目

r($tester->project->getPairsByModel('all', 'noclosed', 50))                && p('50') && e('项目集10 / 项目50'); // 获取所有项目
r($tester->project->getPairsByModel('waterfall', 'noclosed', 11))          && p('12') && e('项目集2 / 项目12');  // 获取所有未关闭的瀑布项目
r($tester->project->getPairsByModel('agileplus', 'noclosed,multiple', 11)) && p('11') && e('项目集1 / 项目11');  // 获取所有未关闭的融合瀑布项目
