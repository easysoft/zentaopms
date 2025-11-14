#!/usr/bin/env php
<?php

/**

title=测试 metricModel::updateMetricFields();
timeout=0
cid=17160

- 执行metricTest模块的updateMetricFieldsTest方法，参数是'1', $metric1  @0
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'2', $metric2  @0
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'3', $metric3  @Exception:
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'999', $metric4  @0
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'5', $metric5  @0
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'6', $metric6  @0
- 执行metricTest模块的updateMetricFieldsTest方法，参数是'7', $metric7  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

zenData('metric')->loadYaml('updatemetricfields/metric', false, 2)->gen(10);

su('admin');

$metricTest = new metricTest();

// 测试步骤1: 正常更新单个字段(name)
$metric1 = new stdClass();
$metric1->name = '更新后的度量项名称';
r($metricTest->updateMetricFieldsTest('1', $metric1)) && p() && e('0');

// 测试步骤2: 正常更新多个字段(name和desc)
$metric2 = new stdClass();
$metric2->name = '更新后的度量项名称2';
$metric2->desc = '更新后的度量项描述2';
r($metricTest->updateMetricFieldsTest('2', $metric2)) && p() && e('0');

// 测试步骤3: 使用空对象更新
$metric3 = new stdClass();
r($metricTest->updateMetricFieldsTest('3', $metric3)) && p() && e('Exception:');

// 测试步骤4: 使用不存在的metricID
$metric4 = new stdClass();
$metric4->name = '不存在的度量项';
r($metricTest->updateMetricFieldsTest('999', $metric4)) && p() && e('0');

// 测试步骤5: 更新包含特殊字符的字段
$metric5 = new stdClass();
$metric5->name = '特殊字符测试<>&"\'';
$metric5->desc = '包含特殊字符的描述: <script>alert("test")</script>';
r($metricTest->updateMetricFieldsTest('5', $metric5)) && p() && e('0');

// 测试步骤6: 更新stage字段为released
$metric6 = new stdClass();
$metric6->stage = 'released';
r($metricTest->updateMetricFieldsTest('6', $metric6)) && p() && e('0');

// 测试步骤7: 更新type字段为sql
$metric7 = new stdClass();
$metric7->type = 'sql';
r($metricTest->updateMetricFieldsTest('7', $metric7)) && p() && e('0');