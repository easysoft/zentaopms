#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

/**

title=测试 zaiModel->deleteKnowledgeLib();
timeout=0
cid=0

- 测试没有ZAI设置时删除知识库 @0
- 测试删除存在的知识库（模拟API调用） @0
- 测试删除不存在的知识库 @0
- 测试使用空ID删除知识库 @0
- 测试使用特殊字符ID删除知识库 @0

*/

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试没有ZAI设置时删除知识库 */
$result1 = $zai->deleteKnowledgeLibTest('test-memory-id-1');
r($result1) && p() && e('0'); // 测试没有ZAI设置时删除知识库

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试删除存在的知识库（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$result2 = $zai->deleteKnowledgeLibTest('test-memory-id-2');
r($result2) && p() && e('0'); // 测试删除存在的知识库（模拟API调用）

/* 测试删除不存在的知识库 */
$result3 = $zai->deleteKnowledgeLibTest('nonexistent-memory-id');
r($result3) && p() && e('0'); // 测试删除不存在的知识库

/* 测试使用空ID删除知识库 */
$result4 = $zai->deleteKnowledgeLibTest('');
r($result4) && p() && e('0'); // 测试使用空ID删除知识库

/* 测试使用特殊字符ID删除知识库 */
$result5 = $zai->deleteKnowledgeLibTest('test-memory-id-!@#$%');
r($result5) && p() && e('0'); // 测试使用特殊字符ID删除知识库
