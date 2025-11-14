#!/usr/bin/env php
<?php
/**

title=测试 programTao::refreshProjectStats();
timeout=0
cid=17707

- 更新项目 11 的统计信息，获取项目 11 的统计信息
 - 属性progress @22.80
 - 属性teamCount @4
 - 属性estimate @51
 - 属性consumed @13
 - 属性left @44
- 更新项目 60 的统计信息，获取项目 60 的统计信息
 - 属性progress @28.00
 - 属性teamCount @0
 - 属性estimate @21
 - 属性consumed @7
 - 属性left @18
- 更新项目 61 的统计信息，获取项目 61 的统计信息
 - 属性progress @0.00
 - 属性teamCount @0
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0
- 更新项目 100 的统计信息，获取项目 100 的统计信息
 - 属性progress @0.00
 - 属性teamCount @0
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0
- 更新不存在的项目 1111 的统计信息，获取不存在的项目 1111 的统计信息
 - 属性progress @0
 - 属性teamCount @0
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('user')->gen(5);

su('admin');

$projectIdList = array(11, 60, 61, 100, 1111);
$programTester = new programTest();

r($programTester->refreshProjectStatsTest($projectIdList[0])) && p('progress,teamCount,estimate,consumed,left') && e('22.80,4,51,13,44'); // 更新项目 11 的统计信息，获取项目 11 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[1])) && p('progress,teamCount,estimate,consumed,left') && e('28.00,0,21,7,18');  // 更新项目 60 的统计信息，获取项目 60 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[2])) && p('progress,teamCount,estimate,consumed,left') && e('0.00,0,0,0,0');     // 更新项目 61 的统计信息，获取项目 61 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[3])) && p('progress,teamCount,estimate,consumed,left') && e('0.00,0,0,0,0');     // 更新项目 100 的统计信息，获取项目 100 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[4])) && p('progress,teamCount,estimate,consumed,left') && e('0,0,0,0,0');        // 更新不存在的项目 1111 的统计信息，获取不存在的项目 1111 的统计信息
