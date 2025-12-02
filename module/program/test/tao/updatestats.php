#!/usr/bin/env php
<?php
/**

title=测试 programTao::updateStats();
timeout=0
cid=17723

- 获取系统中所有项目的任务统计信息
 - 第11条的estimate属性 @51
 - 第11条的left属性 @44
 - 第11条的consumed属性 @13
 - 第11条的teamCount属性 @4
- 获取系统中项目ID=60的任务统计信息
 - 第60条的estimate属性 @21
 - 第60条的left属性 @18
 - 第60条的consumed属性 @7
 - 第60条的teamCount属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('user')->gen(5);
su('admin');

$projectIdList[] = array();
$projectIdList[] = array(60);

$programTester = new programTest();
r($programTester->updateStatsTest($projectIdList[0])) && p('11:estimate,left,consumed,teamCount') && e('51,44,13,4'); // 获取系统中所有项目的任务统计信息
r($programTester->updateStatsTest($projectIdList[1])) && p('60:estimate,left,consumed,teamCount') && e('21,18,7,0');  // 获取系统中项目ID=60的任务统计信息
