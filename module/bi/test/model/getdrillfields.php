#!/usr/bin/env php
<?php

/**

title=测试 biModel::getDrillFields();
timeout=0
cid=15166

- 测试步骤1：正常情况属性name @ZenTao
- 测试步骤2：空钻取数据返回空数组 @0
- 测试步骤3：行索引不存在返回空数组 @0
- 测试步骤4：列键不存在返回空数组 @0
- 测试步骤5：多层嵌套
 - 属性title @Bug Title
 - 属性status @active

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$biTest = new biModelTest();

r($biTest->getDrillFieldsTest(0, 'product', array(0 => array('drillFields' => array('product' => array('name' => 'ZenTao', 'id' => 1)))))) && p('name') && e('ZenTao'); // 测试步骤1：正常情况
r($biTest->getDrillFieldsTest(0, 'product', array())) && p() && e('0'); // 测试步骤2：空钻取数据返回空数组
r($biTest->getDrillFieldsTest(1, 'product', array(0 => array('drillFields' => array('product' => array('name' => 'ZenTao')))))) && p() && e('0'); // 测试步骤3：行索引不存在返回空数组
r($biTest->getDrillFieldsTest(0, 'task', array(0 => array('drillFields' => array('product' => array('name' => 'ZenTao')))))) && p() && e('0'); // 测试步骤4：列键不存在返回空数组
r($biTest->getDrillFieldsTest(0, 'bug', array(0 => array('drillFields' => array('bug' => array('title' => 'Bug Title', 'id' => 100, 'status' => 'active')))))) && p('title,status') && e('Bug Title,active'); // 测试步骤5：多层嵌套