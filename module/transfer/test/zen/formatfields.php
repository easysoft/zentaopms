#!/usr/bin/env php
<?php

/**

title=测试 transferZen::formatFields();
timeout=0
cid=19337

- 执行transferTest模块的formatFieldsTest方法，参数是'story', $normalFields, $sessionData
 - 属性name @field1
 - 属性plan @field3
- 执行transferTest模块的formatFieldsTest方法，参数是'other', $normalFields
 - 属性name @field1
 - 属性branch @field2
 - 属性plan @field3
 - 属性product @field4
- 执行$result
 - 属性name @field1
 - 属性product @field4
- 执行$result
 - 属性name @field1
 - 属性plan @field3
 - 属性product @field4
- 执行$result
 - 属性name @field1
 - 属性plan @field3
 - 属性product @field4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

zenData('product')->loadYaml('product_formatfields', false, 2)->gen(5);
zenData('user')->loadYaml('user', false, 2)->gen(5);

su('admin');

$transferTest = new transferZenTest();

// 准备测试字段数组
$normalFields = array('name' => 'field1', 'branch' => 'field2', 'plan' => 'field3', 'product' => 'field4');

// 测试1: story模块，正常产品类型（ID=1），正常产品应移除branch字段
$sessionData = array('storyTransferParams' => array('productID' => 1), 'storyType' => 'story');
r($transferTest->formatFieldsTest('story', $normalFields, $sessionData)) && p('name,plan') && e('field1,field3');

// 测试2: 其他模块，保留所有字段
r($transferTest->formatFieldsTest('other', $normalFields)) && p('name,branch,plan,product') && e('field1,field2,field3,field4');

// 测试3: story模块，需求类型，plan和branch字段都应被移除
$sessionData = array('storyTransferParams' => array('productID' => 1), 'storyType' => 'requirement');
$result = $transferTest->formatFieldsTest('story', $normalFields, $sessionData);
r($result) && p('name,product') && e('field1,field4');

// 测试4: bug模块，正常产品类型，branch字段应被移除
$sessionData = array('bugTransferParams' => array('productID' => 1));
$result = $transferTest->formatFieldsTest('bug', $normalFields, $sessionData);
r($result) && p('name,plan,product') && e('field1,field3,field4');

// 测试5: testcase模块，正常产品类型，branch字段应被移除
$sessionData = array('testcaseTransferParams' => array('productID' => 1));
$result = $transferTest->formatFieldsTest('testcase', $normalFields, $sessionData);
r($result) && p('name,plan,product') && e('field1,field3,field4');