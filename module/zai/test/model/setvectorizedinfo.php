#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::setVectorizedInfo();
timeout=0
cid=19778

- 测试设置对象类型的向量化信息 @1
- 测试设置字符串类型的向量化信息 @vectorkey001,active,50
- 测试设置后验证信息已保存 @1
- 测试设置空对象的向量化信息 @vectorkey002,inactive,25
- 测试设置复杂对象的向量化信息 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试设置对象类型的向量化信息 */
$vectorInfo1 = new stdClass();
$vectorInfo1->key = 'vectorkey001';
$vectorInfo1->status = 'active';
$vectorInfo1->syncedCount = 50;
r($zai->setVectorizedInfoTest($vectorInfo1)) && p() && e('1'); // 测试设置对象类型的向量化信息

/* 验证设置后的结果 */
r($zai->getVectorizedInfoTest()) && p('key,status,syncedCount') && e('vectorkey001,active,50'); // 测试设置后验证信息已保存

/* 测试设置字符串类型的向量化信息 */
$vectorInfoJson = '{"key":"vectorkey002","status":"inactive","syncedCount":25}';
r($zai->setVectorizedInfoTest($vectorInfoJson)) && p() && e('1'); // 测试设置字符串类型的向量化信息

/* 验证字符串设置后的结果 */
r($zai->getVectorizedInfoTest()) && p('key,status,syncedCount') && e('vectorkey002,inactive,25'); // 测试设置后验证信息已保存

/* 测试设置空对象 */
$emptyInfo = new stdClass();
r($zai->setVectorizedInfoTest($emptyInfo)) && p() && e('1'); // 测试设置空对象的向量化信息

/* 测试设置复杂对象 */
$complexInfo = new stdClass();
$complexInfo->key = 'complexkey999';
$complexInfo->status = 'processing';
$complexInfo->syncedTime = 1640995200;
$complexInfo->syncDetails = new stdClass();
$complexInfo->syncDetails->lastSync = '2022-01-01';
$complexInfo->syncDetails->errors = array('error1', 'error2');
r($zai->setVectorizedInfoTest($complexInfo)) && p() && e('1'); // 测试设置复杂对象的向量化信息
