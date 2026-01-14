#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getMetricRecordType();
timeout=0
cid=17108

- 步骤1：空code返回false @0
- 步骤2：system范围无日期类型 @system
- 步骤3：product范围有日期类型 @scope-date
- 步骤4：project范围无日期类型 @scope
- 步骤5：不存在code非system范围 @scope

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('metric')->gen(0);

su('admin');

$metricTest = new metricModelTest();

r($metricTest->getMetricRecordTypeTest('', 'system')) && p() && e('0'); // 步骤1：空code返回false
r($metricTest->getMetricRecordTypeTest('test_nodate_metric', 'system')) && p() && e('system'); // 步骤2：system范围无日期类型
r($metricTest->getMetricRecordTypeTest('count_of_annual_created_product', 'product')) && p() && e('scope-date'); // 步骤3：product范围有日期类型
r($metricTest->getMetricRecordTypeTest('test_nodate_metric', 'project')) && p() && e('scope'); // 步骤4：project范围无日期类型
r($metricTest->getMetricRecordTypeTest('nonexistent_code', 'execution')) && p() && e('scope'); // 步骤5：不存在code非system范围