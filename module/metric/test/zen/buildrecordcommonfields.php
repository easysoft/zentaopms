#!/usr/bin/env php
<?php

/**

title=测试 metricZen::buildRecordCommonFields();
timeout=0
cid=17182

- 执行metricZenTest模块的buildRecordCommonFieldsZenTest方法，参数是1, 'test_metric', '2023-10-15', array 
 - 属性value @0
 - 属性calcType @cron
 - 属性calculatedBy @system
- 执行metricZenTest模块的buildRecordCommonFieldsZenTest方法，参数是2, 'empty_metric', '2023-10-16', array 
 - 属性value @0
 - 属性calcType @cron
 - 属性calculatedBy @system
- 执行metricZenTest模块的buildRecordCommonFieldsZenTest方法，参数是3, 'multi_field', '2023-10-17', array 
 - 属性year @2023
 - 属性month @10
 - 属性week @42
 - 属性day @17
- 执行metricZenTest模块的buildRecordCommonFieldsZenTest方法，参数是0, 'zero_id', '2023-10-18', array 
 - 属性value @0
 - 属性year @2023
- 执行metricZenTest模块的buildRecordCommonFieldsZenTest方法，参数是5, 'long_metric_code_name', '2023-10-19', array 
 - 属性value @0
 - 属性special @test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricZenTest = new metricZenTest();

// 测试步骤1：正常参数输入构建记录通用字段
r($metricZenTest->buildRecordCommonFieldsZenTest(1, 'test_metric', '2023-10-15', array('year' => 2023, 'month' => 10))) && p('value,calcType,calculatedBy') && e('0,cron,system');

// 测试步骤2：空dateValues数组输入
r($metricZenTest->buildRecordCommonFieldsZenTest(2, 'empty_metric', '2023-10-16', array())) && p('value,calcType,calculatedBy') && e('0,cron,system');

// 测试步骤3：包含多个字段的dateValues输入
r($metricZenTest->buildRecordCommonFieldsZenTest(3, 'multi_field', '2023-10-17', array('year' => 2023, 'month' => 10, 'week' => 42, 'day' => 17))) && p('year,month,week,day') && e('2023,10,42,17');

// 测试步骤4：metricID为0的边界值输入
r($metricZenTest->buildRecordCommonFieldsZenTest(0, 'zero_id', '2023-10-18', array('year' => 2023))) && p('value,year') && e('0,2023');

// 测试步骤5：长字符串code参数输入
r($metricZenTest->buildRecordCommonFieldsZenTest(5, 'long_metric_code_name', '2023-10-19', array('year' => 2023, 'special' => 'test'))) && p('value,special') && e('0,test');