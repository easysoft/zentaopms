#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->getKnowledgeSearchResult();
timeout=0
cid=0

- 严格模式下合法片段结果通过校验 @2
- 严格模式下合法知识结果通过校验 @1
- 非严格模式下失败响应返回空数组 @0
- 严格模式下失败响应抛出运行时异常 @1
- 严格模式下非列表数据抛出运行时异常 @1
- 严格模式下缺少片段内容字段抛出运行时异常 @1
- 严格模式下相似度非法抛出运行时异常 @1
- 严格模式下属性ID非法抛出运行时异常 @1
- 严格模式下key第二段非数字抛出运行时异常 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

su('admin');

$zai = new zaiModelTest();

$chunks = array('result' => 'success', 'data' => array(
    array('chunk_id' => 1, 'content_key' => 'story-1-0', 'chunk_content' => '知识片段一', 'content_attrs' => array('objectType' => 'story', 'objectID' => 1), 'similarity' => 0.95),
    array('chunk_id' => 2, 'content_key' => 'story-2-0', 'chunk_content' => '知识片段二', 'content_attrs' => array('objectType' => 'story', 'knowledgeID' => 2), 'similarity' => 0.85)
));
r($zai->getKnowledgeSearchResultCountTest($chunks, 'chunk', true)) && p() && e('2'); // 严格模式下合法片段结果通过校验

$knowledges = array('result' => 'success', 'data' => array(
    array('id' => 3, 'key' => 'bug-3', 'content' => '缺陷知识', 'attrs' => array('objectType' => 'bug', 'objectID' => 3), 'similarity' => 0.90)
));
r($zai->getKnowledgeSearchResultCountTest($knowledges, 'content', true)) && p() && e('1'); // 严格模式下合法知识结果通过校验

$failed = array('result' => 'fail', 'message' => 'upstream error');
r($zai->getKnowledgeSearchResultCountTest($failed, 'chunk', false)) && p() && e('0'); // 非严格模式下失败响应返回空数组

r($zai->getKnowledgeSearchResultErrorTest($failed, 'chunk', true)) && p() && e('1'); // 严格模式下失败响应抛出运行时异常

$notList = array('result' => 'success', 'data' => array('story-1-0' => array('chunk_id' => 1, 'content_key' => 'story-1-0', 'chunk_content' => '片段', 'content_attrs' => array('objectID' => 1), 'similarity' => 0.90)));
r($zai->getKnowledgeSearchResultErrorTest($notList, 'chunk', true)) && p() && e('1'); // 严格模式下非列表数据抛出运行时异常

$noContent = array('result' => 'success', 'data' => array(
    array('chunk_id' => 1, 'content_key' => 'story-1-0', 'content_attrs' => array('objectID' => 1), 'similarity' => 0.90)
));
r($zai->getKnowledgeSearchResultErrorTest($noContent, 'chunk', true)) && p() && e('1'); // 严格模式下缺少片段内容字段抛出运行时异常

$badSimilarity = array('result' => 'success', 'data' => array(
    array('chunk_id' => 1, 'content_key' => 'story-1-0', 'chunk_content' => '片段', 'content_attrs' => array('objectID' => 1), 'similarity' => 'high')
));
r($zai->getKnowledgeSearchResultErrorTest($badSimilarity, 'chunk', true)) && p() && e('1'); // 严格模式下相似度非法抛出运行时异常

$badAttrs = array('result' => 'success', 'data' => array(
    array('id' => 1, 'key' => 'story-1', 'content' => '知识', 'attrs' => array('objectType' => 'story', 'objectID' => 'abc'), 'similarity' => 0.90)
));
r($zai->getKnowledgeSearchResultErrorTest($badAttrs, 'content', true)) && p() && e('1'); // 严格模式下属性ID非法抛出运行时异常

$badKey = array('result' => 'success', 'data' => array(
    array('id' => 1, 'key' => 'story-abc', 'content' => '知识', 'attrs' => array('objectType' => 'story'), 'similarity' => 0.90)
));
r($zai->getKnowledgeSearchResultErrorTest($badKey, 'content', true)) && p() && e('1'); // 严格模式下key第二段非数字抛出运行时异常
