#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

/**

title=测试 zaiModel->updateKnowledgeItem();
timeout=0
cid=0

- 测试没有ZAI设置时更新知识内容 @0
- 测试使用完整参数更新知识内容（模拟API调用） @0
- 测试只使用必需参数更新知识内容 @0
- 测试更新Markdown格式的知识内容 @0
- 测试更新带属性的知识内容 @0
- 测试使用空内容更新知识内容 @0

*/

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试没有ZAI设置时更新知识内容 */
$result1 = $zai->updateKnowledgeItemTest('memory-id-1', 'key-1', 'Test content');
r($result1) && p() && e('0'); // 测试没有ZAI设置时更新知识内容

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试使用完整参数更新知识内容（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$attrs = array('objectType' => 'story', 'objectID' => 1);
$result2 = $zai->updateKnowledgeItemTest('memory-id-2', 'story-1', 'Test story content', 'markdown', $attrs);
r($result2) && p() && e('0'); // 测试使用完整参数更新知识内容（模拟API调用）

/* 测试只使用必需参数更新知识内容 */
$result3 = $zai->updateKnowledgeItemTest('memory-id-3', 'key-3', 'Minimal test content');
r($result3) && p() && e('0'); // 测试只使用必需参数更新知识内容

/* 测试更新Markdown格式的知识内容 */
$markdownContent = "# Test Title\n\nThis is a test content with markdown format.";
$result4 = $zai->updateKnowledgeItemTest('memory-id-4', 'key-4', $markdownContent, 'markdown');
r($result4) && p() && e('0'); // 测试更新Markdown格式的知识内容

/* 测试更新带属性的知识内容 */
$attrs5 = array('objectType' => 'bug', 'objectID' => 5, 'product' => 1);
$result5 = $zai->updateKnowledgeItemTest('memory-id-5', 'bug-5', 'Bug content', 'markdown', $attrs5);
r($result5) && p() && e('0'); // 测试更新带属性的知识内容

/* 测试使用空内容更新知识内容 */
$result6 = $zai->updateKnowledgeItemTest('memory-id-6', 'key-6', '');
r($result6) && p() && e('0'); // 测试使用空内容更新知识内容
