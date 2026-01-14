#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchLatestMetricRecords();
timeout=0
cid=17162

- 步骤1：空参数测试，返回0 @0
- 步骤2：空字符串参数测试，返回0 @0
- 步骤3：空参数系统级测试，返回0 @0
- 步骤4：空字符串执行级测试，返回0 @0
- 步骤5：空参数项目级无分页测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
// 使用默认的metric和metriclib数据
zenData('metric')->gen(50);
zenData('metriclib')->gen(100);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试基本功能，验证方法的异常处理和边界情况
r($metricTest->fetchLatestMetricRecordsTest(null, array('product'))) && p() && e('0'); // 步骤1：空参数测试，返回0
r($metricTest->fetchLatestMetricRecordsTest('', array('product'))) && p() && e('0'); // 步骤2：空字符串参数测试，返回0
r($metricTest->fetchLatestMetricRecordsTest(null, array('system'))) && p() && e('0'); // 步骤3：空参数系统级测试，返回0
r($metricTest->fetchLatestMetricRecordsTest('', array('execution'))) && p() && e('0'); // 步骤4：空字符串执行级测试，返回0
r($metricTest->fetchLatestMetricRecordsTest(null, array('project'), array(), null)) && p() && e('0'); // 步骤5：空参数项目级无分页测试