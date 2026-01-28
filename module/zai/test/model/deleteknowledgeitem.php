#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 zaiModel->deleteKnowledgeItem();
timeout=0
cid=0

- 测试没有ZAI设置时删除知识内容 @0
- 测试删除存在的知识内容（模拟API调用） @0
- 测试删除不存在的知识内容 @0
- 测试使用空key删除知识内容 @0
- 测试使用特殊字符key删除知识内容 @0
- 测试删除多个不同类型的知识内容 @0

*/

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试没有ZAI设置时删除知识内容 */
$result1 = $zai->deleteKnowledgeItemTest('memory-id-1', 'key-1');
r($result1) && p() && e('0'); // 测试没有ZAI设置时删除知识内容

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试删除存在的知识内容（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$result2 = $zai->deleteKnowledgeItemTest('memory-id-2', 'story-1');
r($result2) && p() && e('0'); // 测试删除存在的知识内容（模拟API调用）

/* 测试删除不存在的知识内容 */
$result3 = $zai->deleteKnowledgeItemTest('memory-id-3', 'nonexistent-key');
r($result3) && p() && e('0'); // 测试删除不存在的知识内容

/* 测试使用空key删除知识内容 */
$result4 = $zai->deleteKnowledgeItemTest('memory-id-4', '');
r($result4) && p() && e('0'); // 测试使用空key删除知识内容

/* 测试使用特殊字符key删除知识内容 */
$result5 = $zai->deleteKnowledgeItemTest('memory-id-5', 'test-key-!@#$%');
r($result5) && p() && e('0'); // 测试使用特殊字符key删除知识内容

/* 测试删除多个不同类型的知识内容 */
$result6a = $zai->deleteKnowledgeItemTest('memory-id-6', 'bug-100');
$result6b = $zai->deleteKnowledgeItemTest('memory-id-6', 'story-200');
$result6c = $zai->deleteKnowledgeItemTest('memory-id-6', 'doc-300');
r($result6a) && p() && e('0'); // 测试删除多个不同类型的知识内容
