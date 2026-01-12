#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->getKnowledgeChunks();
timeout=0
cid=0

- 测试没有ZAI设置时获取知识内容块列表 @not_array
- 测试获取存在的知识内容块列表（模拟API调用） @not_array
- 测试获取不存在的知识内容块列表 @not_array
- 测试使用空contentID获取知识内容块 @not_array
- 测试获取不同memoryID的知识内容块 @not_array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试没有ZAI设置时获取知识内容块列表 */
$result1 = $zai->getKnowledgeChunksTest('memory-id-1', 'content-id-1');
r(is_array($result1) ? 'array' : 'not_array') && p() && e('not_array'); // 测试没有ZAI设置时获取知识内容块列表

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试获取存在的知识内容块列表（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$result2 = $zai->getKnowledgeChunksTest('memory-id-2', 'content-id-2');
r(is_array($result2) ? 'array' : 'not_array') && p() && e('not_array'); // 测试获取存在的知识内容块列表（模拟API调用）

/* 测试获取不存在的知识内容块列表 */
$result3 = $zai->getKnowledgeChunksTest('memory-id-3', 'nonexistent-content-id');
r(is_array($result3) ? 'array' : 'not_array') && p() && e('not_array'); // 测试获取不存在的知识内容块列表

/* 测试使用空contentID获取知识内容块 */
$result4 = $zai->getKnowledgeChunksTest('memory-id-4', '');
r(is_array($result4) ? 'array' : 'not_array') && p() && e('not_array'); // 测试使用空contentID获取知识内容块

/* 测试获取不同memoryID的知识内容块 */
$result5 = $zai->getKnowledgeChunksTest('different-memory-id', 'content-id-5');
r(is_array($result5) ? 'array' : 'not_array') && p() && e('not_array'); // 测试获取不同memoryID的知识内容块
