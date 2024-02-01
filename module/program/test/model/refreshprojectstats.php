#!/usr/bin/env php
<?php
/**

title=测试 programTao::refreshProjectStats();
timeout=0
cid=1

- 更新项目 11 的统计信息，获取项目 11 的统计信息
 - 属性progress @25.00
 - 属性teamCount @4
 - 属性estimate @46
 - 属性consumed @13
 - 属性left @39
- 更新项目 60 的统计信息，获取项目 60 的统计信息
 - 属性progress @31.80
 - 属性teamCount @0
 - 属性estimate @18
 - 属性consumed @7
 - 属性left @15
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
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('project')->config('program')->gen(20);
zdTable('task')->config('task')->gen(20);
zdTable('team')->config('team')->gen(30);
zdTable('user')->gen(5);

su('admin');

$projectIdList = array(11, 60, 61, 100, 1111);
$programTester = new programTest();

r($programTester->refreshProjectStatsTest($projectIdList[0])) && p('progress,teamCount,estimate,consumed,left') && e('25.00,4,46,13,39'); // 更新项目 11 的统计信息，获取项目 11 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[1])) && p('progress,teamCount,estimate,consumed,left') && e('31.80,0,18,7,15');  // 更新项目 60 的统计信息，获取项目 60 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[2])) && p('progress,teamCount,estimate,consumed,left') && e('0.00,0,0,0,0');     // 更新项目 61 的统计信息，获取项目 61 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[3])) && p('progress,teamCount,estimate,consumed,left') && e('0.00,0,0,0,0');     // 更新项目 100 的统计信息，获取项目 100 的统计信息
r($programTester->refreshProjectStatsTest($projectIdList[4])) && p('progress,teamCount,estimate,consumed,left') && e('0,0,0,0,0');        // 更新不存在的项目 1111 的统计信息，获取不存在的项目 1111 的统计信息
