#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getRowSpanConfig();
timeout=0
cid=0

- 空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 步骤1：测试正常记录数据处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'test1', 'rowSpan' => 2),
        array('value' => 'test2', 'rowSpan' => 1)
    )
))) && p('0:0,0:1') && e('2,1'); // 正常情况

// 步骤2：测试包含数组值的记录处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('item1', 'item2', 'item3'), 'rowSpan' => 2),
        array('value' => 'normal', 'rowSpan' => 1)
    )
))) && p() && e('3'); // 根据数组长度生成配置

// 步骤3：测试空记录数组处理
r($pivotTest->getRowSpanConfigTest(array())) && p() && e('0'); // 空数组

// 步骤4：测试记录中无数组值的情况
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => 'string1', 'rowSpan' => 3),
        array('value' => 'string2', 'rowSpan' => 2)
    )
))) && p('0:0,0:1') && e('3,2'); // 默认配置

// 步骤5：测试多个记录与数组值混合处理
r($pivotTest->getRowSpanConfigTest(array(
    array(
        array('value' => array('a', 'b'), 'rowSpan' => 1),
        array('value' => 'text', 'rowSpan' => 2)
    )
))) && p() && e('2'); // 数组长度为2