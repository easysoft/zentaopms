#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genCluBar();
timeout=0
cid=15565

- 执行$result1) && isset($result1['series'][0]['name'] @1
- 执行$result2) && isset($result2['series'][0]['stack'] @1
- 执行$result3) && $result3['xAxis']['type'] == 'value @1
- 执行$result4) && isset($result4['series'][0]['data'][0] @1
- 执行$result5) && isset($result5['series'][0]['name'] @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$chartTest = new chartTest();

// 4. 执行测试步骤(至少5个)
$result1 = $chartTest->genCluBarTest('normal');
$result2 = $chartTest->genCluBarTest('stackedBar');
$result3 = $chartTest->genCluBarTest('cluBarY');
$result4 = $chartTest->genCluBarTest('withFilters');
$result5 = $chartTest->genCluBarTest('withLangs');

r(is_array($result1) && isset($result1['series'][0]['name'])) && p() && e('1');
r(is_array($result2) && isset($result2['series'][0]['stack'])) && p() && e('1');
r(is_array($result3) && $result3['xAxis']['type'] == 'value') && p() && e('1');
r(is_array($result4) && isset($result4['series'][0]['data'][0])) && p() && e('1');
r(is_array($result5) && isset($result5['series'][0]['name'])) && p() && e('1');