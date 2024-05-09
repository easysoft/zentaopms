#!/usr/bin/env php
<?php
/**

title=测试 programTao::getTaskStats();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('user')->gen(5);
su('admin');

$projectIdList[] = array();
$projectIdList[] = array(60);

$programTester = new programTest();
r($programTester->getTaskStatsTest($projectIdList[0])) && p('11:totalEstimate,totalConsumed,totalLeft,teamCount,totalLeftNotDel,totalConsumedNotDel') && e('46,13,39,0,39,13'); // 获取系统中所有项目的任务统计信息
r($programTester->getTaskStatsTest($projectIdList[1])) && p('60:totalEstimate,totalConsumed,totalLeft,teamCount,totalLeftNotDel,totalConsumedNotDel') && e('18,7,15,0,15,7');   // 获取系统中项目ID=60的任务统计信息
