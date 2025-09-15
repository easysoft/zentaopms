#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRecordsForDisplay();
timeout=0
cid=0

- 步骤1：正常情况名称第0条的name属性 @产品A
- 步骤1：正常情况数量第0条的count属性 @10
- 步骤1：正常情况价格第0条的price属性 @123.456
- 步骤2：总计标识转换第0条的name属性 @总计
- 步骤2：总计数量第0条的count属性 @100
- 步骤3：百分比处理名称第0条的name属性 @产品C
- 步骤3：百分比处理第0条的rate属性 @25%
- 步骤4：数组值展开第二行第1条的category属性 @STORY
- 步骤5：空记录处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

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
r($pivotTest->processRecordsForDisplayTest($normalRecords)) && p('0:count') && e('10'); // 步骤1：正常情况数量
r($pivotTest->processRecordsForDisplayTest($normalRecords)) && p('0:price') && e('123.456'); // 步骤1：正常情况价格

// 步骤2：测试含有 $total$ 标识的记录处理
$totalRecords = array(
    array(
        'name' => array('value' => '$total$'),
        'count' => array('value' => 100)
    )
);
r($pivotTest->processRecordsForDisplayTest($totalRecords)) && p('0:name') && e('总计'); // 步骤2：总计标识转换
r($pivotTest->processRecordsForDisplayTest($totalRecords)) && p('0:count') && e('100'); // 步骤2：总计数量

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
r($pivotTest->processRecordsForDisplayTest($percentageRecords)) && p('0:name') && e('产品C'); // 步骤3：百分比处理名称
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