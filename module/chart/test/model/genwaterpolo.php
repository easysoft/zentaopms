#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genWaterpolo();
timeout=0
cid=15569

- 执行$result1
 - 第series[0]条的type属性 @liquidFill
 - 第tooltip条的show属性 @1
- 执行$result2['series'][0]['data'][0]) ? $result2['series'][0]['data'][0] :  @0
- 执行$result3['series'][0]['data'][0]) ? $result3['series'][0]['data'][0] :  @0.95
- 执行$result4['series'][0]['data'][0]) ? $result4['series'][0]['data'][0] :  @0.05
- 执行$result5['series'][0]['data'][0]) ? $result5['series'][0]['data'][0] :  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

zenData('chart')->loadYaml('chart')->gen(50);
zenData('module')->loadYaml('module')->gen(27)->fixPath();
zenData('user')->gen(5);
su('admin');

$chart = new chartTest();

$result1 = $chart->genWaterpoloTest('normal');
$result2 = $chart->genWaterpoloTest('zeroPercent');
$result3 = $chart->genWaterpoloTest('highPercent');
$result4 = $chart->genWaterpoloTest('lowPercent');
$result5 = $chart->genWaterpoloTest('exactOne');

r($result1) && p('series[0]:type;tooltip:show') && e('liquidFill;1');
r(isset($result2['series'][0]['data'][0]) ? $result2['series'][0]['data'][0] : '') && p() && e('0');
r(isset($result3['series'][0]['data'][0]) ? $result3['series'][0]['data'][0] : '') && p() && e('0.95');
r(isset($result4['series'][0]['data'][0]) ? $result4['series'][0]['data'][0] : '') && p() && e('0.05');
r(isset($result5['series'][0]['data'][0]) ? $result5['series'][0]['data'][0] : '') && p() && e('1');