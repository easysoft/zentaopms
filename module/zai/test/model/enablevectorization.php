#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->enableVectorization();
timeout=0
cid=19769

- 测试在没有ZAI设置时启用向量化 @fail
- 测试向量化已启用时再次启用 @fail
- 测试强制启用已启用的向量化 @fail
- 测试设置ZAI配置后启用向量化 @fail
- 测试禁用状态下强制启用向量化 @fail

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试在没有ZAI设置时启用向量化 */
r($zai->enableVectorizationTest()) && p('result') && e('fail'); // 测试在没有ZAI设置时启用向量化

/* 设置ZAI配置但API调用会失败 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 设置向量化信息为已启用状态 */
$vectorInfo = new stdClass();
$vectorInfo->key = 'existingkey123';
$vectorInfo->status = 'enabled';
$vectorInfo->syncedTime = time();
$vectorInfo->syncedCount = 10;
$vectorInfo->syncFailedCount = 0;
$vectorInfo->syncTime = 0;
$vectorInfo->syncingType = 'story';
$vectorInfo->syncingID = 0;
$vectorInfo->syncDetails = new stdClass();
$vectorInfo->createdAt = time();
$vectorInfo->createdBy = 'admin';
$zai->setVectorizedInfoTest($vectorInfo);

/* 测试向量化已启用时再次启用 */
r($zai->enableVectorizationTest()) && p('result') && e('fail'); // 测试向量化已启用时再次启用

/* 测试强制启用已启用的向量化（无真实ZAI服务时失败） */
r($zai->enableVectorizationTest(true)) && p('result') && e('fail'); // 测试强制启用已启用的向量化

/* 重置向量化状态为禁用 */
$disabledVectorInfo = new stdClass();
$disabledVectorInfo->key = '';
$disabledVectorInfo->status = 'disabled';
$disabledVectorInfo->syncedTime = 0;
$disabledVectorInfo->syncedCount = 0;
$disabledVectorInfo->syncFailedCount = 0;
$disabledVectorInfo->syncTime = 0;
$disabledVectorInfo->syncingType = 'story';
$disabledVectorInfo->syncingID = 0;
$disabledVectorInfo->syncDetails = new stdClass();
$disabledVectorInfo->createdAt = time();
$disabledVectorInfo->createdBy = 'admin';
$zai->setVectorizedInfoTest($disabledVectorInfo);

/* 测试设置ZAI配置后启用向量化（无真实ZAI服务时失败） */
r($zai->enableVectorizationTest()) && p('result') && e('fail'); // 测试设置ZAI配置后启用向量化

/* 测试禁用状态下强制启用向量化（无真实ZAI服务时失败） */
r($zai->enableVectorizationTest(true)) && p('result') && e('fail'); // 测试禁用状态下强制启用向量化
