#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRecordsForDisplay();
timeout=0
cid=17426

- 步骤1：正常情况名称第0条的name属性 @产品A
- 步骤2：总计标识转换第0条的name属性 @总计
- 步骤3：百分比处理第0条的rate属性 @25%
- 步骤4：数组值展开第二行第1条的category属性 @STORY
- 步骤5：空记录处理 @0
- 步骤6：整数值处理第0条的value属性 @42
- 步骤7：独占百分比添加后缀第0条的rate_percentage属性 @50%

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常数据记录处理
$normalRecords = array(
    array(
        'name' => array('value' => '产品A'),
        'count' => array('value' => 10),
        'price' => array('value' => 123.456)
    )
);
r($pivotTest->processRecordsForDisplayTest($normalRecords)) && p('0:name') && e('产品A'); // 步骤1：正常情况名称

// 步骤2：测试含有 $total$ 标识的记录处理
$totalRecords = array(
    array(
        'name' => array('value' => '$total$'),
        'count' => array('value' => 100)
    )
);
r($pivotTest->processRecordsForDisplayTest($totalRecords)) && p('0:name') && e('总计'); // 步骤2：总计标识转换

// 步骤3：测试含有百分比数据的记录处理
$percentageRecords = array(
    array(
        'name' => array('value' => '产品C'),
        'rate' => array(
            'value' => 25,
            'percentage' => array(25, 100, '', false)
        )
    )
);
r($pivotTest->processRecordsForDisplayTest($percentageRecords)) && p('0:rate') && e('25%'); // 步骤3：百分比处理

// 步骤4：测试含有数组值的记录处理
$arrayRecords = array(
    array(
        'category' => array('value' => array('BUG', 'STORY')),
        'count' => array('value' => array(5, 3))
    )
);
r($pivotTest->processRecordsForDisplayTest($arrayRecords)) && p('1:category') && e('STORY'); // 步骤4：数组值展开第二行

// 步骤5：测试空记录处理
$emptyRecords = array();
r($pivotTest->processRecordsForDisplayTest($emptyRecords)) && p() && e('0'); // 步骤5：空记录处理

// 步骤6：测试数字精度处理（整数应保持为整数）
$integerRecords = array(
    array(
        'value' => array('value' => 42.0),
        'price' => array('value' => 99.99)
    )
);
r($pivotTest->processRecordsForDisplayTest($integerRecords)) && p('0:value') && e('42'); // 步骤6：整数值处理

// 步骤7：测试独占百分比处理
$monopolizeRecords = array(
    array(
        'item' => array('value' => 'TestItem'),
        'rate' => array(
            'value' => 50,
            'percentage' => array(50, 100, '', true)
        )
    )
);
r($pivotTest->processRecordsForDisplayTest($monopolizeRecords)) && p('0:rate_percentage') && e('50%'); // 步骤7：独占百分比添加后缀