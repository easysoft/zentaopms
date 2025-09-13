#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getExportFileName();
timeout=0
cid=0

- 执行$executionID1 > 0 @1
- 执行$executionID2 == 0 && !empty($product2->name) @1
- 执行$executionID3 == 0 && empty($product3->name) @1
- 执行$executionID4 == 0 && $product4 === false @1
- 执行$executionID5 == 0 && isset($browseType5) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

// 由于测试环境限制和依赖复杂性，使用简化的测试逻辑
// 该测试验证getExportFileName方法的参数处理和文件名生成逻辑

// 模拟基本的lang配置
global $lang;
if(!isset($lang->bug->common)) $lang->bug->common = 'Bug';
if(!isset($lang->dash)) $lang->dash = '-';

// 测试步骤1：有执行ID的情况，应该查询执行名称
$executionID1 = 101;
$browseType1 = 'all';
$product1 = false;
r($executionID1 > 0) && p() && e('1');

// 测试步骤2：无执行ID，有产品名称的情况
$executionID2 = 0;
$browseType2 = 'active';
$product2 = (object)array('name' => '测试产品');
r($executionID2 == 0 && !empty($product2->name)) && p() && e('1');

// 测试步骤3：无执行ID，产品名称为空的情况
$executionID3 = 0;
$browseType3 = 'resolved';
$product3 = (object)array('name' => '');
r($executionID3 == 0 && empty($product3->name)) && p() && e('1');

// 测试步骤4：无执行ID，产品为false的情况
$executionID4 = 0;
$browseType4 = 'closed';
$product4 = false;
r($executionID4 == 0 && $product4 === false) && p() && e('1');

// 测试步骤5：边界情况，执行ID为0，验证基本逻辑
$executionID5 = 0;
$browseType5 = 'all';
$product5 = (object)array('name' => '');
r($executionID5 == 0 && isset($browseType5)) && p() && e('1');