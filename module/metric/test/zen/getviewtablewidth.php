#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getViewTableWidth();
timeout=0
cid=17196

- 步骤1：空数组边界测试 @1
- 步骤2：正常headers包含width属性（100+200+50+1=351） @351
- 步骤3：部分headers缺少width属性（100+160+50+1=311） @311
- 步骤4：特殊值测试width为0和负数（0+(-50)+100+1=51） @51
- 步骤5：全部headers无width属性（160*3+1=481） @481
- 步骤6：单个header测试（250+1=251） @251
- 步骤7：混合数据类型测试（100+120+160+1=381） @381
- 步骤8：大数值width测试（5000+3000+1=8001） @8001
- 步骤9：浮点数width测试（150.5+200.8+1=352.3） @352.3
- 步骤10：空header数组内容测试（160+100+160+1=421） @421

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$testData1 = array();
r($metricTest->getViewTableWidthZenTest($testData1)) && p() && e(1); // 步骤1：空数组边界测试

$testData2 = array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2', 'width' => 200),
    array('name' => 'col3', 'width' => 50)
);
r($metricTest->getViewTableWidthZenTest($testData2)) && p() && e(351); // 步骤2：正常headers包含width属性（100+200+50+1=351）

$testData3 = array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2'),
    array('name' => 'col3', 'width' => 50)
);
r($metricTest->getViewTableWidthZenTest($testData3)) && p() && e(311); // 步骤3：部分headers缺少width属性（100+160+50+1=311）

$testData4 = array(
    array('name' => 'col1', 'width' => 0),
    array('name' => 'col2', 'width' => -50),
    array('name' => 'col3', 'width' => 100)
);
r($metricTest->getViewTableWidthZenTest($testData4)) && p() && e(51); // 步骤4：特殊值测试width为0和负数（0+(-50)+100+1=51）

$testData5 = array(
    array('name' => 'col1'),
    array('name' => 'col2'),
    array('name' => 'col3')
);
r($metricTest->getViewTableWidthZenTest($testData5)) && p() && e(481); // 步骤5：全部headers无width属性（160*3+1=481）

$testData6 = array(
    array('name' => 'single', 'width' => 250)
);
r($metricTest->getViewTableWidthZenTest($testData6)) && p() && e(251); // 步骤6：单个header测试（250+1=251）

$testData7 = array(
    array('title' => 'differentKey', 'width' => 100),
    array('label' => 'anotherKey', 'width' => 120),
    array('text' => 'thirdKey')
);
r($metricTest->getViewTableWidthZenTest($testData7)) && p() && e(381); // 步骤7：混合数据类型测试（100+120+160+1=381）

$testData8 = array(
    array('name' => 'big1', 'width' => 5000),
    array('name' => 'big2', 'width' => 3000)
);
r($metricTest->getViewTableWidthZenTest($testData8)) && p() && e(8001); // 步骤8：大数值width测试（5000+3000+1=8001）

$testData9 = array(
    array('name' => 'float1', 'width' => 150.5),
    array('name' => 'float2', 'width' => 200.8)
);
r($metricTest->getViewTableWidthZenTest($testData9)) && p() && e(352.3); // 步骤9：浮点数width测试（150.5+200.8+1=352.3）

$testData10 = array(
    array(),
    array('name' => 'normal', 'width' => 100),
    array('name' => 'empty')
);
r($metricTest->getViewTableWidthZenTest($testData10)) && p() && e(421); // 步骤10：空header数组内容测试（160+100+160+1=421）