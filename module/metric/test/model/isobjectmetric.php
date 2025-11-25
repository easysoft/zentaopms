#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isObjectMetric();
timeout=0
cid=17141

- 步骤1：测试系统度量项（不含scope字段） @false
- 步骤2：测试包含scope字段的度量项 @true
- 步骤3：测试包含多个字段包括scope的度量项 @true
- 步骤4：测试空数组输入 @false
- 步骤5：测试嵌套数组结构中包含scope字段 @true
- 步骤6：测试字段名包含scope但name属性为其他值的情况 @false
- 步骤7：测试大小写敏感的scope字段 @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';
su('admin');

$metricTest = new metricTest();

// 测试数据1：系统度量项（不含scope字段）
$systemHeader = array();
$systemHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$systemHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$systemHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);

// 测试数据2：包含scope字段的度量项
$objectHeader = array();
$objectHeader[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$objectHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$objectHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$objectHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

// 测试数据3：包含多个字段包括scope的度量项
$multiFieldHeader = array();
$multiFieldHeader[] = array('name' => 'id', 'title' => 'ID', 'width' => 50);
$multiFieldHeader[] = array('name' => 'scope', 'title' => '项目名称', 'width' => 160);
$multiFieldHeader[] = array('name' => 'status', 'title' => '状态', 'width' => 80);
$multiFieldHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$multiFieldHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

// 测试数据4：空数组
$emptyHeader = array();

// 测试数据5：嵌套数组结构中包含scope字段
$nestedHeader = array();
$nestedHeader[] = array('name' => 'scope', 'title' => '范围', 'width' => 100, 'type' => 'object');
$nestedHeader[] = array('name' => 'metrics', 'title' => '指标', 'width' => 200);

// 测试数据6：字段名包含scope但name属性为其他值的情况
$noScopeNameHeader = array();
$noScopeNameHeader[] = array('name' => 'product', 'title' => '产品范围', 'width' => 160);
$noScopeNameHeader[] = array('name' => 'project_scope', 'title' => '项目范围', 'width' => 160);
$noScopeNameHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);

// 测试数据7：大小写敏感的scope字段
$uppercaseScopeHeader = array();
$uppercaseScopeHeader[] = array('name' => 'SCOPE', 'title' => '范围', 'width' => 160);
$uppercaseScopeHeader[] = array('name' => 'Scope', 'title' => '范围', 'width' => 160);
$uppercaseScopeHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);

r($metricTest->isObjectMetric($systemHeader)) && p('') && e('false');           // 步骤1：测试系统度量项（不含scope字段）
r($metricTest->isObjectMetric($objectHeader)) && p('') && e('true');            // 步骤2：测试包含scope字段的度量项
r($metricTest->isObjectMetric($multiFieldHeader)) && p('') && e('true');        // 步骤3：测试包含多个字段包括scope的度量项
r($metricTest->isObjectMetric($emptyHeader)) && p('') && e('false');            // 步骤4：测试空数组输入
r($metricTest->isObjectMetric($nestedHeader)) && p('') && e('true');            // 步骤5：测试嵌套数组结构中包含scope字段
r($metricTest->isObjectMetric($noScopeNameHeader)) && p('') && e('false');      // 步骤6：测试字段名包含scope但name属性为其他值的情况
r($metricTest->isObjectMetric($uppercaseScopeHeader)) && p('') && e('false');   // 步骤7：测试大小写敏感的scope字段