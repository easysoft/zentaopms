#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getVectorizedInfo();
timeout=0
cid=19776

- 测试获取不存在的向量化信息 @disabled
- 测试默认向量化信息的key字段 @~~
- 测试默认向量化信息的同步计数字段 @0
- 测试设置向量化信息后获取 @1
- 测试验证向量化信息的所有字段 @testvectorkey123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试获取不存在的向量化信息 */
r($zai->getVectorizedInfoTest()) && p('status') && e('disabled'); // 测试获取不存在的向量化信息

/* 测试默认向量化信息的各个字段 */
r($zai->getVectorizedInfoTest()) && p('key') && e('~~'); // 测试默认向量化信息的key字段

r($zai->getVectorizedInfoTest()) && p('syncedCount') && e('0'); // 测试默认向量化信息的同步计数字段

r($zai->getVectorizedInfoTest()) && p('syncFailedCount') && e('0'); // 测试默认向量化信息的同步计数字段

/* 设置向量化信息后再获取 */
$vectorizedInfo = new stdClass();
$vectorizedInfo->key = 'testvectorkey123';
$vectorizedInfo->status = 'enabled';
$vectorizedInfo->syncedTime = time();
$vectorizedInfo->syncedCount = 100;
$vectorizedInfo->syncFailedCount = 5;

$tester->loadModel('setting')->setItem('system.zai.kb.systemVectorization', json_encode($vectorizedInfo));

$getResult = $zai->getVectorizedInfoTest();
r(isset($getResult->key) ? 1 : 0) && p() && e('1'); // 测试设置向量化信息后获取

/* 测试验证向量化信息的所有字段 */
r($getResult) && p('key') && e('testvectorkey123'); // 测试验证向量化信息的所有字段
