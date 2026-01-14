#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDatasetPath();
timeout=0
cid=17088

- 步骤1：正常获取dataset路径 @dataset.php
- 步骤2：验证文件扩展名 @.php
- 步骤3：验证包含metric模块名 @1
- 步骤4：验证包含dataset文件名 @1
- 步骤5：验证完整路径格式 @module/metric/dataset.php

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

r(substr($metricTest->getDatasetPathTest(), -11)) && p() && e('dataset.php'); // 步骤1：正常获取dataset路径
r(substr($metricTest->getDatasetPathTest(), -4)) && p() && e('.php'); // 步骤2：验证文件扩展名
r(strpos($metricTest->getDatasetPathTest(), 'metric') !== false ? '1' : '0') && p() && e('1'); // 步骤3：验证包含metric模块名
r(strpos($metricTest->getDatasetPathTest(), 'dataset.php') !== false ? '1' : '0') && p() && e('1'); // 步骤4：验证包含dataset文件名
r(substr($metricTest->getDatasetPathTest(), -25)) && p() && e('module/metric/dataset.php'); // 步骤5：验证完整路径格式