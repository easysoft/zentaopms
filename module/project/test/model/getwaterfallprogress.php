#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('project')->config('execution')->gen(30);

/**

title=测试 projectModel->getWaterfallProgress();
timeout=0
cid=1

*/

$projectIdList[0] = array(1);
$projectIdList[1] = array(11);
$projectIdList[2] = array(100);
$projectIdList[3] = array(60, 61);

global $tester;
$projectModule = $tester->loadModel('project');

r($projectModule->getWaterfallProgress($projectIdList[0])) && p()     && e('0'); // 测试获取不存在项目的进度
r($projectModule->getWaterfallProgress($projectIdList[1])) && p()     && e('0'); // 测试获取敏捷项目的进度
r($projectModule->getWaterfallProgress($projectIdList[2])) && p()     && e('0'); // 测试获取看板项目的进度
r($projectModule->getWaterfallProgress($projectIdList[3])) && p('60') && e('0'); // 测试获取瀑布项目的进度
