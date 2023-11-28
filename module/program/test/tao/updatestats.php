#!/usr/bin/env php
<?php
/**

title=测试 programTao::updateStats();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('project')->config('program')->gen(20);
zdTable('task')->config('task')->gen(20);
zdTable('team')->config('team')->gen(30);
zdTable('user')->gen(5);
su('admin');

$projectIdList[] = array();
$projectIdList[] = array(60);

$programTester = new programTest();
r($programTester->updateStatsTest($projectIdList[0])) && p('11:estimate,left,consumed,teamCount') && e('46,39,13,4'); // 获取系统中所有项目的任务统计信息
r($programTester->updateStatsTest($projectIdList[1])) && p('60:estimate,left,consumed,teamCount') && e('18,15,7,0');  // 获取系统中项目ID=60的任务统计信息
