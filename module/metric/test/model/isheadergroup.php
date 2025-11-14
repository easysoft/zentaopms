#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isHeaderGroup();
timeout=0
cid=17140

- 步骤1：测试包含headerGroup属性的header数组 @1
- 步骤2：测试不包含headerGroup属性的header数组 @0
- 步骤3：测试空header数组 @0
- 步骤4：测试null值输入 @0
- 步骤5：测试包含多个headerGroup的复杂header数组 @1
- 步骤6：测试包含空headerGroup值的header数组 @1
- 步骤7：测试只有一个元素包含headerGroup的混合数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

// 测试数据准备：包含headerGroup属性的header数组
$headerWithGroup = array();
$headerWithGroup[] = array('name' => 'scope', 'title' => 'projectname', 'fixed' => 'left', 'width' => 128);
$headerWithGroup[] = array('name' => '2023-12-19', 'title' => '12-19', 'headerGroup' => '2023年', 'align' => 'center', 'width' => 68);
$headerWithGroup[] = array('name' => '2023-12-16', 'title' => '12-16', 'headerGroup' => '2023年', 'align' => 'center', 'width' => 68);

// 测试数据准备：不包含headerGroup属性的header数组
$headerWithoutGroup = array();
$headerWithoutGroup[] = array('name' => 'scope', 'title' => 'projectname', 'fixed' => 'left', 'width' => 128);
$headerWithoutGroup[] = array('name' => '2023-12-19', 'title' => '2023', 'align' => 'center', 'width' => 68);

// 测试数据准备：空header数组
$emptyHeader = array();

// 测试数据准备：包含多个headerGroup的复杂header数组
$complexHeaderWithGroup = array();
$complexHeaderWithGroup[] = array('name' => 'scope', 'title' => 'scope', 'width' => 100);
$complexHeaderWithGroup[] = array('name' => 'jan', 'title' => '1月', 'headerGroup' => '第一季度', 'width' => 80);
$complexHeaderWithGroup[] = array('name' => 'feb', 'title' => '2月', 'headerGroup' => '第一季度', 'width' => 80);
$complexHeaderWithGroup[] = array('name' => 'mar', 'title' => '3月', 'headerGroup' => '第一季度', 'width' => 80);

// 测试数据准备：包含空headerGroup值的header数组
$headerWithEmptyGroup = array();
$headerWithEmptyGroup[] = array('name' => 'scope', 'title' => 'scope', 'width' => 100);
$headerWithEmptyGroup[] = array('name' => 'value', 'title' => 'value', 'headerGroup' => '', 'width' => 80);

// 测试数据准备：只有一个元素包含headerGroup的混合数组
$mixedHeaderWithGroup = array();
$mixedHeaderWithGroup[] = array('name' => 'scope', 'title' => 'scope', 'width' => 100);
$mixedHeaderWithGroup[] = array('name' => 'value1', 'title' => 'value1', 'width' => 80);
$mixedHeaderWithGroup[] = array('name' => 'value2', 'title' => 'value2', 'headerGroup' => '分组', 'width' => 80);

r($metric->isHeaderGroup($headerWithGroup)) && p() && e('1');           // 步骤1：测试包含headerGroup属性的header数组
r($metric->isHeaderGroup($headerWithoutGroup)) && p() && e('0');         // 步骤2：测试不包含headerGroup属性的header数组
r($metric->isHeaderGroup($emptyHeader)) && p() && e('0');                // 步骤3：测试空header数组
r($metric->isHeaderGroup(null)) && p() && e('0');                       // 步骤4：测试null值输入
r($metric->isHeaderGroup($complexHeaderWithGroup)) && p() && e('1');     // 步骤5：测试包含多个headerGroup的复杂header数组
r($metric->isHeaderGroup($headerWithEmptyGroup)) && p() && e('1');       // 步骤6：测试包含空headerGroup值的header数组
r($metric->isHeaderGroup($mixedHeaderWithGroup)) && p() && e('1');       // 步骤7：测试只有一个元素包含headerGroup的混合数组