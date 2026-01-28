#!/usr/bin/env php
<?php
/**

title=测试 programTao::getTaskStats();
timeout=0
cid=17720

- 获取系统中所有项目的任务统计信息
 - 第11条的totalEstimate属性 @51
 - 第11条的totalConsumed属性 @13
 - 第11条的totalLeft属性 @44
 - 第11条的teamCount属性 @0
 - 第11条的totalLeftNotDel属性 @44
 - 第11条的totalConsumedNotDel属性 @13
- 获取系统中项目ID=60的任务统计信息
 - 第60条的totalEstimate属性 @21
 - 第60条的totalConsumed属性 @7
 - 第60条的totalLeft属性 @18
 - 第60条的teamCount属性 @0
 - 第60条的totalLeftNotDel属性 @18
 - 第60条的totalConsumedNotDel属性 @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('user')->gen(5);
su('admin');

$projectIdList[] = array();
$projectIdList[] = array(60);

$programTester = new programTaoTest();
r($programTester->getTaskStatsTest($projectIdList[0])) && p('11:totalEstimate,totalConsumed,totalLeft,teamCount,totalLeftNotDel,totalConsumedNotDel') && e('51,13,44,0,44,13'); // 获取系统中所有项目的任务统计信息
r($programTester->getTaskStatsTest($projectIdList[1])) && p('60:totalEstimate,totalConsumed,totalLeft,teamCount,totalLeftNotDel,totalConsumedNotDel') && e('21,7,18,0,18,7');   // 获取系统中项目ID=60的任务统计信息
