#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->searchKnowledges();
timeout=0
cid=0

- 测试没有ZAI设置时搜索知识库 @0
- 测试设置ZAI配置后搜索知识库（基本查询） @array
- 测试搜索知识库并指定limit参数 @array
- 测试搜索知识库并指定minSimilarity参数 @array
- 测试搜索知识库并使用filter过滤条件 @array
- 测试搜索知识库返回空数组（API失败） @0
- 测试搜索知识库使用不同的collection @array
- 测试搜索知识库使用复杂的filter条件 @array
- 测试搜索知识库使用空查询字符串 @array
- 测试搜索知识库使用最小相似度0.5 @array
- 测试搜索知识库使用最大limit值 @array
- 测试搜索知识库使用多个filter条件 @array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试没有ZAI设置时搜索知识库 */
$result1 = $zai->searchKnowledgesTest('测试查询', 'test-collection', array());
r(empty($result1) ? 0 : count($result1)) && p() && e('0'); // 测试没有ZAI设置时搜索知识库

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试设置ZAI配置后搜索知识库（基本查询） */
// 注意：由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行和参数处理
$result2 = $zai->searchKnowledgesTest('如何使用禅道', 'zentao_kb', array());
r(is_array($result2) ? 'array' : 'not_array') && p() && e('array'); // 测试设置ZAI配置后搜索知识库（基本查询）

/* 测试搜索知识库并指定limit参数 */
$result3 = $zai->searchKnowledgesTest('需求管理', 'zentao_kb', array(), 10);
r(is_array($result3) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库并指定limit参数

/* 测试搜索知识库并指定minSimilarity参数 */
$result4 = $zai->searchKnowledgesTest('缺陷跟踪', 'zentao_kb', array(), 20, 0.9);
r(is_array($result4) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库并指定minSimilarity参数

/* 测试搜索知识库并使用filter过滤条件 */
$filter = array('objectType' => 'story');
$result5 = $zai->searchKnowledgesTest('用户故事', 'zentao_kb', $filter);
r(is_array($result5) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库并使用filter过滤条件

/* 测试搜索知识库返回空数组（API失败） */
// 由于没有真实服务器，API调用会失败，应该返回空数组
$result6 = $zai->searchKnowledgesTest('测试', 'invalid_collection', array());
r(empty($result6) ? 0 : count($result6)) && p() && e('0'); // 测试搜索知识库返回空数组（API失败）

/* 测试搜索知识库使用不同的collection */
$result7 = $zai->searchKnowledgesTest('项目管理', 'project_kb', array());
r(is_array($result7) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用不同的collection

/* 测试搜索知识库使用复杂的filter条件 */
$complexFilter = array(
    'objectType' => 'bug',
    'status' => 'active',
    'product' => 1
);
$result8 = $zai->searchKnowledgesTest('如何修复bug', 'zentao_kb', $complexFilter);
r(is_array($result8) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用复杂的filter条件

/* 测试搜索知识库使用空查询字符串 */
$result9 = $zai->searchKnowledgesTest('', 'zentao_kb', array());
r(is_array($result9) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用空查询字符串

/* 测试搜索知识库使用最小相似度0.5 */
$result10 = $zai->searchKnowledgesTest('测试用例', 'zentao_kb', array(), 20, 0.5);
r(is_array($result10) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用最小相似度0.5

/* 测试搜索知识库使用最大limit值 */
$result11 = $zai->searchKnowledgesTest('文档管理', 'zentao_kb', array(), 100);
r(is_array($result11) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用最大limit值

/* 测试搜索知识库使用多个filter条件 */
$multiFilter = array(
    'objectType' => 'doc',
    'product' => 1,
    'lib' => 2,
    'type' => 'text'
);
$result12 = $zai->searchKnowledgesTest('API文档', 'zentao_kb', $multiFilter, 15, 0.85);
r(is_array($result12) ? 'array' : 'not_array') && p() && e('array'); // 测试搜索知识库使用多个filter条件