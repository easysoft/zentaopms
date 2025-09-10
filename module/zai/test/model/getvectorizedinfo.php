#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getVectorizedInfo();
timeout=0
cid=0

- 测试获取不存在的向量化信息 @disabled
- 测试默认向量化信息的key字段 @~~
- 测试默认向量化信息的同步计数字段 @0
- 测试设置向量化信息后获取 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

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

r($zai->getVectorizedInfoTest()) && p('key,status,syncedCount,syncFailedCount') && e('testvectorkey123,enabled,100,5'); // 测试设置向量化信息后获取
