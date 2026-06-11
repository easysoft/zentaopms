#!/usr/bin/env php
<?php
/**

title=测试 programTao::updateStats();
timeout=0
cid=17723

- 获取系统中所有项目的任务统计信息
 - 第11条的estimate属性 @51.00
 - 第11条的left属性 @44.00
 - 第11条的consumed属性 @13.00
 - 第11条的teamCount属性 @4
- 获取系统中项目ID=60的任务统计信息
 - 第60条的estimate属性 @21.00
 - 第60条的left属性 @18.00
 - 第60条的consumed属性 @7.00
 - 第60条的teamCount属性 @0
- 获取系统中项目ID=61的任务统计信息
 - 第61条的estimate属性 @0.00
 - 第61条的left属性 @0.00
 - 第61条的consumed属性 @0.00
 - 第61条的teamCount属性 @0
- 获取系统中项目ID=100的任务统计信息
 - 第100条的estimate属性 @0.00
 - 第100条的left属性 @0.00
 - 第100条的consumed属性 @0.00
 - 第100条的teamCount属性 @0
- 获取系统中项目ID=101的任务统计信息
 - 第101条的estimate属性 @0
 - 第101条的left属性 @0
 - 第101条的consumed属性 @0
 - 第101条的teamCount属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('user')->gen(5);
su('admin');

$projectIdList[] = array();
$projectIdList[] = array(60);
$projectIdList[] = array(61);
$projectIdList[] = array(100);
$projectIdList[] = array(101);

$programTester = new programTaoTest();
r($programTester->updateStatsTest($projectIdList[0])) && p('11:estimate,left,consumed,teamCount')  && e('51.00,44.00,13.00,4'); // 获取系统中所有项目的任务统计信息
r($programTester->updateStatsTest($projectIdList[1])) && p('60:estimate,left,consumed,teamCount')  && e('21.00,18.00,7.00,0');  // 获取系统中项目ID=60的任务统计信息
r($programTester->updateStatsTest($projectIdList[2])) && p('61:estimate,left,consumed,teamCount')  && e('0.00,0.00,0.00,0');    // 获取系统中项目ID=61的任务统计信息
r($programTester->updateStatsTest($projectIdList[3])) && p('100:estimate,left,consumed,teamCount') && e('0.00,0.00,0.00,0');    // 获取系统中项目ID=100的任务统计信息
r($programTester->updateStatsTest($projectIdList[4])) && p('101:estimate,left,consumed,teamCount') && e('0,0,0,0');             // 获取系统中项目ID=101的任务统计信息
