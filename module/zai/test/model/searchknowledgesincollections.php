#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->searchKnowledgesInCollections();
timeout=0
cid=0

- 测试没有ZAI设置时在多个知识库中搜索 @0
- 测试设置ZAI配置后在多个知识库中搜索（基本查询） @array
- 测试在多个知识库中搜索（content类型） @array
- 测试在多个知识库中搜索（chunk类型） @array
- 测试在多个知识库中搜索并指定limit参数 @array
- 测试在多个知识库中搜索并指定minSimilarity参数 @array
- 测试在单个知识库中搜索 @array
- 测试在空filters时搜索 @0
- 测试使用空查询字符串搜索 @array
- 测试使用最小相似度0.5搜索 @array
- 测试使用最大limit值搜索 @array
- 测试使用复杂filter条件搜索 @array

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试没有ZAI设置时在多个知识库中搜索 */
$filters = array(
    'global' => array('objectType' => 'story')
);
$result1 = $zai->searchKnowledgesInCollectionsTest('测试查询', $filters);
r(empty($result1) ? 0 : count($result1)) && p() && e('0'); // 测试没有ZAI设置时在多个知识库中搜索

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 设置向量化信息（全局知识库key） */
$vectorizedInfo = new stdClass();
$vectorizedInfo->key = 'test_global_collection_key';
$vectorizedInfo->status = 'enabled';
$vectorizedInfo->syncedTime = time();
$vectorizedInfo->syncedCount = 100;
$vectorizedInfo->syncFailedCount = 0;
$vectorizedInfo->syncTime = 0;
$vectorizedInfo->syncingType = 'story';
$vectorizedInfo->syncingID = 0;
$vectorizedInfo->syncDetails = new stdClass();
$vectorizedInfo->createdAt = time();
$vectorizedInfo->createdBy = 'admin';
$tester->loadModel('setting')->setItem('system.zai.kb.systemVectorization', json_encode($vectorizedInfo));

/* 测试设置ZAI配置后在多个知识库中搜索（基本查询） */
$filters2 = array(
    'global' => array('objectType' => 'story')
);
$result2 = $zai->searchKnowledgesInCollectionsTest('如何使用禅道', $filters2);
r(is_array($result2) ? 'array' : 'not_array') && p() && e('array'); // 测试设置ZAI配置后在多个知识库中搜索（基本查询）

/* 测试在多个知识库中搜索（content类型） */
$filters3 = array(
    'global' => array('objectType' => 'story', 'status' => 'active')
);
$result3 = $zai->searchKnowledgesInCollectionsTest('需求管理', $filters3, 'content');
r(is_array($result3) ? 'array' : 'not_array') && p() && e('array'); // 测试在多个知识库中搜索（content类型）

/* 测试在多个知识库中搜索（chunk类型） */
$filters4 = array(
    'global' => array('objectType' => 'bug')
);
$result4 = $zai->searchKnowledgesInCollectionsTest('缺陷跟踪', $filters4, 'chunk');
r(is_array($result4) ? 'array' : 'not_array') && p() && e('array'); // 测试在多个知识库中搜索（chunk类型）

/* 测试在多个知识库中搜索并指定limit参数 */
$filters5 = array(
    'global' => array('objectType' => 'doc')
);
$result5 = $zai->searchKnowledgesInCollectionsTest('文档管理', $filters5, 'content', 10);
r(is_array($result5) ? 'array' : 'not_array') && p() && e('array'); // 测试在多个知识库中搜索并指定limit参数

/* 测试在多个知识库中搜索并指定minSimilarity参数 */
$filters6 = array(
    'global' => array('objectType' => 'story')
);
$result6 = $zai->searchKnowledgesInCollectionsTest('用户故事', $filters6, 'content', 20, 0.9);
r(is_array($result6) ? 'array' : 'not_array') && p() && e('array'); // 测试在多个知识库中搜索并指定minSimilarity参数

/* 测试在单个知识库中搜索 */
$filters7 = array(
    'global' => array('objectType' => 'task')
);
$result7 = $zai->searchKnowledgesInCollectionsTest('任务管理', $filters7);
r(is_array($result7) ? 'array' : 'not_array') && p() && e('array'); // 测试在单个知识库中搜索

/* 测试在空filters时搜索 */
$filters8 = array();
$result8 = $zai->searchKnowledgesInCollectionsTest('测试', $filters8);
r(empty($result8) ? 0 : count($result8)) && p() && e('0'); // 测试在空filters时搜索

/* 测试使用空查询字符串搜索 */
$filters9 = array(
    'global' => array()
);
$result9 = $zai->searchKnowledgesInCollectionsTest('', $filters9);
r(is_array($result9) ? 'array' : 'not_array') && p() && e('array'); // 测试使用空查询字符串搜索

/* 测试使用最小相似度0.5搜索 */
$filters10 = array(
    'global' => array('objectType' => 'feedback')
);
$result10 = $zai->searchKnowledgesInCollectionsTest('用户反馈', $filters10, 'content', 20, 0.5);
r(is_array($result10) ? 'array' : 'not_array') && p() && e('array'); // 测试使用最小相似度0.5搜索

/* 测试使用最大limit值搜索 */
$filters11 = array(
    'global' => array('objectType' => 'design')
);
$result11 = $zai->searchKnowledgesInCollectionsTest('设计文档', $filters11, 'content', 100);
r(is_array($result11) ? 'array' : 'not_array') && p() && e('array'); // 测试使用最大limit值搜索

/* 测试使用复杂filter条件搜索 */
$filters12 = array(
    'global' => array(
        'objectType' => 'story',
        'product' => 1,
        'status' => 'active',
        'stage' => 'developing'
    )
);
$result12 = $zai->searchKnowledgesInCollectionsTest('产品需求', $filters12, 'content', 15, 0.85);
r(is_array($result12) ? 'array' : 'not_array') && p() && e('array'); // 测试使用复杂filter条件搜索
