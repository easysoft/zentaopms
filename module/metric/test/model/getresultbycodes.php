#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getResultByCodes();
timeout=0
cid=17124

- 步骤1：正常情况测试有效代码数组属性count_of_product @0
- 步骤2：空数组测试 @0
- 步骤3：不存在的代码测试 @0
- 步骤4：部分有效代码测试属性count_of_product @0
- 步骤5：带选项参数测试属性count_of_product @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

zenData('metric')->loadYaml('metric_getresultbycodes', false, 2)->gen(10);

su('admin');

$metricTest = new metricTest();

r($metricTest->getResultByCodesTest(array('count_of_product', 'count_of_project'))) && p('count_of_product') && e('0'); // 步骤1：正常情况测试有效代码数组
r($metricTest->getResultByCodesTest(array())) && p() && e('0'); // 步骤2：空数组测试
r($metricTest->getResultByCodesTest(array('non_existent_code1', 'non_existent_code2'))) && p() && e('0'); // 步骤3：不存在的代码测试
r($metricTest->getResultByCodesTest(array('count_of_product', 'non_existent_code'))) && p('count_of_product') && e('0'); // 步骤4：部分有效代码测试
r($metricTest->getResultByCodesTest(array('count_of_product', 'count_of_project'), array('product' => '1,2'))) && p('count_of_product') && e('0'); // 步骤5：带选项参数测试