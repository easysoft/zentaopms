#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getVolumesMetrics();
timeout=0
cid=15624

- 步骤1:使用有效的实例对象获取卷度量
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 步骤2:使用空spaceData的实例对象
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 步骤3:使用空k8name的实例对象
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 步骤4:验证返回对象包含必需属性 @1
- 步骤5:验证rate计算结果合理性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 准备测试用的实例对象
$validInstance = new stdClass();
$validInstance->spaceData = new stdClass();
$validInstance->spaceData->k8space = 'default';
$validInstance->k8name = 'test-app';

// 步骤1:使用有效的实例对象获取卷度量
$result1 = $cneTest->getVolumesMetricsTest($validInstance);

// 步骤2:使用空spaceData的实例对象
$instance2 = new stdClass();
$instance2->spaceData = new stdClass();
$instance2->spaceData->k8space = '';
$instance2->k8name = 'test-app';
$result2 = $cneTest->getVolumesMetricsTest($instance2);

// 步骤3:使用空k8name的实例对象
$instance3 = new stdClass();
$instance3->spaceData = new stdClass();
$instance3->spaceData->k8space = 'default';
$instance3->k8name = '';
$result3 = $cneTest->getVolumesMetricsTest($instance3);

// 步骤4:验证返回对象包含必需属性
$result4 = $cneTest->getVolumesMetricsTest($validInstance);
$hasLimit = isset($result4->limit);
$hasUsage = isset($result4->usage);
$hasRate = isset($result4->rate);

// 步骤5:验证rate计算结果合理性
$result5 = $cneTest->getVolumesMetricsTest($validInstance);
$rateIsNumeric = is_numeric($result5->rate);

r($result1) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤1:使用有效的实例对象获取卷度量
r($result2) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤2:使用空spaceData的实例对象
r($result3) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤3:使用空k8name的实例对象
r($hasLimit && $hasUsage && $hasRate) && p() && e('1'); // 步骤4:验证返回对象包含必需属性
r($rateIsNumeric) && p() && e('1'); // 步骤5:验证rate计算结果合理性