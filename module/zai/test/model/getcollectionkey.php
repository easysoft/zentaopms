#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getCollectionKey();
timeout=0
cid=0

- 测试获取不存在的 global collection key @0
- 测试设置向量化信息后获取 global collection key @test_collection_key_123
- 测试设置空 key 的向量化信息 @0
- 测试非 global collection 参数（数字） @0
- 测试非 global collection 参数（字符串） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试获取不存在的 global collection key */
r($zai->getCollectionKeyTest('global')) && p() && e('0'); // 测试获取不存在的 global collection key

/* 设置向量化信息后获取 global collection key */
$vectorizedInfo = new stdClass();
$vectorizedInfo->key = 'test_collection_key_123';
$vectorizedInfo->status = 'enabled';
$vectorizedInfo->syncedTime = time();
$vectorizedInfo->syncedCount = 50;
$vectorizedInfo->syncFailedCount = 2;

$tester->loadModel('setting')->setItem('system.zai.kb.systemVectorization', json_encode($vectorizedInfo));

r($zai->getCollectionKeyTest('global')) && p() && e('test_collection_key_123'); // 测试设置向量化信息后获取 global collection key

/* 测试设置空 key 的向量化信息 */
$vectorizedInfo2 = new stdClass();
$vectorizedInfo2->key = '';
$vectorizedInfo2->status = 'disabled';
$vectorizedInfo2->syncedTime = time();

$tester->loadModel('setting')->setItem('system.zai.kb.systemVectorization', json_encode($vectorizedInfo2));

r($zai->getCollectionKeyTest('global')) && p() && e('0'); // 测试设置空 key 的向量化信息

/* 测试非 global collection 参数 */
r($zai->getCollectionKeyTest(123)) && p() && e('0'); // 测试非 global collection 参数（数字）

r($zai->getCollectionKeyTest('other')) && p() && e('0'); // 测试非 global collection 参数（字符串）
