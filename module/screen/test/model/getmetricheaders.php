#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getMetricHeaders();
timeout=0
cid=18247

- 步骤1：年份类型返回单行表头数量 @1
- 步骤2：月份类型返回双行表头数量 @2
- 步骤3：空输入返回空数组第一行 @0
- 步骤4：年份类型第一行元素数量 @3
- 步骤5：月份类型第二行元素数量 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 测试数据准备
$resultHeaderYear = array(
    array('name' => '2023-01', 'headerGroup' => '2023', 'title' => '2023年1月'),
    array('name' => '2023-02', 'headerGroup' => '2023', 'title' => '2023年2月')
);

$resultHeaderMonth = array(
    array('name' => '2023-01', 'headerGroup' => '2023', 'title' => '2023年1月'),
    array('name' => '2023-02', 'headerGroup' => '2023', 'title' => '2023年2月')
);

$emptyHeader = array();

// 4. 测试步骤（必须包含至少5个测试步骤）
r(count($screenTest->getMetricHeadersTest($resultHeaderYear, 'year'))) && p() && e('1'); // 步骤1：年份类型返回单行表头数量

r(count($screenTest->getMetricHeadersTest($resultHeaderMonth, 'month'))) && p() && e('2'); // 步骤2：月份类型返回双行表头数量

r(count($screenTest->getMetricHeadersTest($emptyHeader, 'year')[0])) && p() && e('0'); // 步骤3：空输入返回空数组第一行

r(count($screenTest->getMetricHeadersTest($resultHeaderYear, 'year')[0])) && p() && e('3'); // 步骤4：年份类型第一行元素数量

r(count($screenTest->getMetricHeadersTest($resultHeaderMonth, 'month')[1])) && p() && e('2'); // 步骤5：月份类型第二行元素数量