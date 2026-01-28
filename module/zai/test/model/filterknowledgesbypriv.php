#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->filterKnowledgesByPriv();
timeout=0
cid=0

- 测试过滤空数组 @0
- 测试过滤content类型的知识（有权限） @1
- 测试过滤content类型的知识（无权限） @0
- 测试过滤chunk类型的知识（有权限） @1
- 测试过滤chunk类型的知识（无权限） @0
- 测试过滤多个content类型的知识（混合权限） @2
- 测试使用limit参数过滤 @2
- 测试使用limit参数过滤超出范围 @3
- 测试过滤story类型的知识 @1
- 测试过滤bug类型的知识 @1
- 测试过滤doc类型的知识 @1
- 测试过滤feedback类型的知识 @1
- 测试过滤无效key格式的知识 @0
- 测试过滤包含attrs的知识 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('story')->gen(10);
zenData('bug')->gen(10);
zenData('task')->gen(10);
zenData('doc')->gen(10);
zenData('feedback')->gen(10);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试过滤空数组 */
$result1 = $zai->filterKnowledgesByPrivTest(array());
r(count($result1)) && p() && e('0'); // 测试过滤空数组

/* 测试过滤content类型的知识（有权限） */
$knowledges2 = array(
    array(
        'key' => 'story-1',
        'content' => '这是一个测试故事',
        'similarity' => 0.95,
        'attrs' => array('objectType' => 'story', 'objectID' => 1, 'product' => 1)
    )
);
$result2 = $zai->filterKnowledgesByPrivTest($knowledges2, 'content');
r(count($result2)) && p() && e('1'); // 测试过滤content类型的知识（有权限）

/* 测试过滤content类型的知识（无权限） */
$knowledges3 = array(
    array(
        'key' => 'story-999',
        'content' => '这是一个没有权限的故事',
        'similarity' => 0.95,
        'attrs' => array('objectType' => 'story', 'objectID' => 999, 'product' => 999)
    )
);
$result3 = $zai->filterKnowledgesByPrivTest($knowledges3, 'content');
r(count($result3)) && p() && e('0'); // 测试过滤content类型的知识（无权限）

/* 测试过滤chunk类型的知识（有权限） */
$knowledges4 = array(
    array(
        'content_key' => 'story-1',
        'content' => '这是一个测试故事的片段',
        'similarity' => 0.92,
        'content_attrs' => array('objectType' => 'story', 'objectID' => 1, 'product' => 1)
    )
);
$result4 = $zai->filterKnowledgesByPrivTest($knowledges4, 'chunk');
r(count($result4)) && p() && e('1'); // 测试过滤chunk类型的知识（有权限）

/* 测试过滤chunk类型的知识（无权限） */
$knowledges5 = array(
    array(
        'content_key' => 'story-999',
        'content' => '这是一个没有权限的故事片段',
        'similarity' => 0.92,
        'content_attrs' => array('objectType' => 'story', 'objectID' => 999, 'product' => 999)
    )
);
$result5 = $zai->filterKnowledgesByPrivTest($knowledges5, 'chunk');
r(count($result5)) && p() && e('0'); // 测试过滤chunk类型的知识（无权限）

/* 测试过滤多个content类型的知识（混合权限） */
$knowledges6 = array(
    array(
        'key' => 'story-1',
        'content' => '有权限的故事1',
        'similarity' => 0.95,
        'attrs' => array('objectType' => 'story', 'objectID' => 1, 'product' => 1)
    ),
    array(
        'key' => 'story-999',
        'content' => '无权限的故事',
        'similarity' => 0.90,
        'attrs' => array('objectType' => 'story', 'objectID' => 999, 'product' => 999)
    ),
    array(
        'key' => 'story-2',
        'content' => '有权限的故事2',
        'similarity' => 0.88,
        'attrs' => array('objectType' => 'story', 'objectID' => 2, 'product' => 1)
    )
);
$result6 = $zai->filterKnowledgesByPrivTest($knowledges6, 'content');
r(count($result6)) && p() && e('2'); // 测试过滤多个content类型的知识（混合权限）

/* 测试使用limit参数过滤 */
$knowledges8 = array(
    array('key' => 'story-1', 'content' => '故事1', 'similarity' => 0.95),
    array('key' => 'story-2', 'content' => '故事2', 'similarity' => 0.90),
    array('key' => 'story-3', 'content' => '故事3', 'similarity' => 0.85),
    array('key' => 'story-4', 'content' => '故事4', 'similarity' => 0.80)
);
$result8 = $zai->filterKnowledgesByPrivTest($knowledges8, 'content', 2);
r(count($result8)) && p() && e('2'); // 测试使用limit参数过滤

/* 测试使用limit参数过滤超出范围 */
$knowledges9 = array(
    array('key' => 'story-1', 'content' => '故事1', 'similarity' => 0.95),
    array('key' => 'story-2', 'content' => '故事2', 'similarity' => 0.90),
    array('key' => 'story-3', 'content' => '故事3', 'similarity' => 0.85)
);
$result9 = $zai->filterKnowledgesByPrivTest($knowledges9, 'content', 10);
r(count($result9)) && p() && e('3'); // 测试使用limit参数过滤超出范围

/* 测试过滤story类型的知识 */
$knowledges10 = array(
    array(
        'key' => 'story-1',
        'content' => '需求故事',
        'similarity' => 0.92,
        'attrs' => array('objectType' => 'story', 'objectID' => 1, 'product' => 1)
    )
);
$result10 = $zai->filterKnowledgesByPrivTest($knowledges10, 'content');
r(count($result10)) && p() && e('1'); // 测试过滤story类型的知识

/* 测试过滤bug类型的知识 */
$knowledges11 = array(
    array(
        'key' => 'bug-1',
        'content' => '缺陷报告',
        'similarity' => 0.91,
        'attrs' => array('objectType' => 'bug', 'objectID' => 1, 'product' => 1)
    )
);
$result11 = $zai->filterKnowledgesByPrivTest($knowledges11, 'content');
r(count($result11)) && p() && e('1'); // 测试过滤bug类型的知识

/* 测试过滤doc类型的知识 */
$knowledges12 = array(
    array(
        'key' => 'doc-1',
        'content' => '文档内容',
        'similarity' => 0.90,
        'attrs' => array('objectType' => 'doc', 'objectID' => 1)
    )
);
$result12 = $zai->filterKnowledgesByPrivTest($knowledges12, 'content');
r(count($result12)) && p() && e('1'); // 测试过滤doc类型的知识

/* 测试过滤feedback类型的知识 */
$knowledges14 = array(
    array(
        'key' => 'feedback-1',
        'content' => '用户反馈',
        'similarity' => 0.88,
        'attrs' => array('objectType' => 'feedback', 'objectID' => 1, 'product' => 1)
    )
);
$result14 = $zai->filterKnowledgesByPrivTest($knowledges14, 'content');
r(count($result14)) && p() && e('1'); // 测试过滤feedback类型的知识

/* 测试过滤无效key格式的知识 */
$knowledges15 = array(
    array(
        'key' => 'invalid',
        'content' => '无效的key格式',
        'similarity' => 0.95
    ),
    array(
        'key' => '',
        'content' => '空key',
        'similarity' => 0.90
    )
);
$result15 = $zai->filterKnowledgesByPrivTest($knowledges15, 'content');
r(count($result15)) && p() && e('0'); // 测试过滤无效key格式的知识

/* 测试过滤包含attrs的知识 */
$knowledges16 = array(
    array(
        'key' => 'story-1',
        'content' => '带attrs的故事',
        'similarity' => 0.93,
        'attrs' => array(
            'objectType' => 'story',
            'objectID' => 1,
            'product' => 1,
            'status' => 'active',
            'stage' => 'developing'
        )
    )
);
$result16 = $zai->filterKnowledgesByPrivTest($knowledges16, 'content');
r(count($result16)) && p() && e('1'); // 测试过滤包含attrs的知识
