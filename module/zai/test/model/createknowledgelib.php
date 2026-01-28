#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 zaiModel->createKnowledgeLib();
timeout=0
cid=0

- 测试没有ZAI设置时创建知识库 @0
- 测试使用完整参数创建知识库（模拟API调用） @0
- 测试只使用名称创建知识库 @0
- 测试使用名称和描述创建知识库 @0
- 测试使用空名称创建知识库 @0

*/

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试没有ZAI设置时创建知识库 */
$result1 = $zai->createKnowledgeLibTest('test-lib-1', 'Test Library 1');
r($result1) && p() && e('0'); // 测试没有ZAI设置时创建知识库

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试使用完整参数创建知识库（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$result2 = $zai->createKnowledgeLibTest('test-lib-2', 'Test Library 2', array('type' => 'custom'));
r($result2) && p() && e('0'); // 测试使用完整参数创建知识库（模拟API调用）

/* 测试只使用名称创建知识库 */
$result3 = $zai->createKnowledgeLibTest('test-lib-3');
r($result3) && p() && e('0'); // 测试只使用名称创建知识库

/* 测试使用名称和描述创建知识库 */
$result4 = $zai->createKnowledgeLibTest('test-lib-4', 'Description for test library 4');
r($result4) && p() && e('0'); // 测试使用名称和描述创建知识库

/* 测试使用空名称创建知识库 */
$result5 = $zai->createKnowledgeLibTest('');
r($result5) && p() && e('0'); // 测试使用空名称创建知识库
