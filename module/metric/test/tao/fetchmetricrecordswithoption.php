#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchMetricRecordsWithOption();
timeout=0
cid=17167

- 步骤1：正常获取度量数据 @0
- 步骤2：使用options过滤execution @0
- 步骤3：空options参数 @0
- 步骤4：不存在的code @0
- 步骤5：多个options过滤 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
zenData('metric')->loadYaml('metric', false, 2)->gen(5);
zenData('metriclib')->loadYaml('metriclib', false, 2)->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$metricTest = new metricTaoTest();

// 5. 执行测试步骤
r($metricTest->fetchMetricRecordsWithOptionTest('storyScale', array('project', 'value'))) && p() && e('0'); // 步骤1：正常获取度量数据
r($metricTest->fetchMetricRecordsWithOptionTest('taskProgress', array('execution', 'value'), array('execution' => array(1, 2)))) && p() && e('0'); // 步骤2：使用options过滤execution
r($metricTest->fetchMetricRecordsWithOptionTest('bugDensity', array('product', 'value'), array())) && p() && e('0'); // 步骤3：空options参数
r($metricTest->fetchMetricRecordsWithOptionTest('non_existent_code', array('value'))) && p() && e('0'); // 步骤4：不存在的code
r($metricTest->fetchMetricRecordsWithOptionTest('codeQuality', array('system'), array('system' => array(1)))) && p() && e('0'); // 步骤5：多个options过滤