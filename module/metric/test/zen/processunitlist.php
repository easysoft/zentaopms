#!/usr/bin/env php
<?php

/**

title=测试 metricZen::processUnitList();
timeout=0
cid=17204

- 执行metric模块的unitList['measure']方法  @工时
- 执行metric模块的unitList['measure']方法  @故事点
- 执行metric模块的unitList['measure']方法  @功能点
- 执行metric模块的unitList['measure']方法  @工时
- 执行metric模块的unitList['measure']方法  @工时

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricZenTest = new metricZenTest();

// 测试步骤1：测试默认情况（hourPoint配置未设置，使用默认值0）
global $config, $lang;
unset($config->custom->hourPoint);
$metricZenTest->processUnitListZenTest();
r($lang->metric->unitList['measure']) && p() && e('工时');

// 测试步骤2：测试hourPoint配置为1（故事点）
$config->custom = new stdclass();
$config->custom->hourPoint = '1';
$metricZenTest->processUnitListZenTest();
r($lang->metric->unitList['measure']) && p() && e('故事点');

// 测试步骤3：测试hourPoint配置为2（功能点）
$config->custom->hourPoint = '2';
$metricZenTest->processUnitListZenTest();
r($lang->metric->unitList['measure']) && p() && e('功能点');

// 测试步骤4：测试hourPoint未设置时使用默认值0 
unset($config->custom->hourPoint);
$metricZenTest->processUnitListZenTest();
r($lang->metric->unitList['measure']) && p() && e('工时');

// 测试步骤5：测试验证custom语言包被正确加载
$config->custom->hourPoint = '0';
$metricZenTest->processUnitListZenTest();
r($lang->metric->unitList['measure']) && p() && e('工时');