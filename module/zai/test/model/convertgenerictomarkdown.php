#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertGenericToMarkdown();
timeout=0
cid=0

- 测试转换包含标题的对象 @101
- 测试标题包含类型和ID @1
- 测试内容 JSON 包含 title @1
- 测试内容 JSON 包含 extra @1
- 测试标题回退到 name 字段 @1
- 测试缺少 ID 时的默认处理属性id @1
- 测试缺少 ID 时的默认处理属性title @1
- 测试未知类型时的标题格式 @1
- 测试内容保持原始字符串 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
global $app;

$app->loadLang('zai');

$zai = new zaiTest();

$app->lang->zai->syncingTypeList['custom'] = '自定义类型';

$generic1 = new stdClass();
$generic1->id    = 101;
$generic1->title = '泛化对象标题';
$generic1->name  = '泛化对象名称';
$generic1->extra = 'extraValue';

/* 测试转换包含标题的对象 */
$result1 = $zai->convertGenericToMarkdownTest('custom', $generic1);
r($result1) && p('id') && e('101'); // 测试转换包含标题的对象

/* 测试标题包含类型和ID */
$titleContainsTypeAndId = strpos($result1['title'], '自定义类型 #101') !== false;
r($titleContainsTypeAndId) && p() && e('1'); // 测试标题包含类型和ID

/* 测试内容 JSON 包含 title */
$contentData1 = json_decode($result1['content'], true);
$jsonContainsTitle = isset($contentData1['title']) && $contentData1['title'] === '泛化对象标题';
r($jsonContainsTitle) && p() && e('1'); // 测试内容 JSON 包含 title

/* 测试内容 JSON 包含 extra */
$jsonContainsExtra = isset($contentData1['extra']) && $contentData1['extra'] === 'extraValue';
r($jsonContainsExtra) && p() && e('1'); // 测试内容 JSON 包含 extra

$generic2 = new stdClass();
$generic2->id   = 202;
$generic2->name = '名称对象';
$generic2->status = 'active';

/* 测试标题回退到 name 字段 */
$result2 = $zai->convertGenericToMarkdownTest('custom', $generic2);
$titleFallbackName = strpos($result2['title'], '自定义类型 #202 名称对象') !== false;
r($titleFallbackName) && p() && e('1'); // 测试标题回退到 name 字段

$generic3 = new stdClass();
$generic3->name = '无ID对象';
$generic3->note = '缺少ID';

/* 测试缺少 ID 时的默认处理 */
$result3 = $zai->convertGenericToMarkdownTest('custom', $generic3);
$idFallbackZero = isset($result3['id']) && $result3['id'] === 0;
r($idFallbackZero) && p() && e('1'); // 测试缺少 ID 时的默认处理属性id

/* 测试缺少 ID 时的默认处理属性title */
$titleFallbackZero = strpos($result3['title'], '自定义类型 #0 无ID对象') !== false;
r($titleFallbackZero) && p() && e('1'); // 测试缺少 ID 时的默认处理属性title

$generic4 = new stdClass();
$generic4->id    = 303;
$generic4->title = '未知类型对象';
$generic4->flag  = true;

/* 测试未知类型时的标题格式 */
$result4 = $zai->convertGenericToMarkdownTest('unknownType', $generic4);
$unknownTypeTitle = strpos($result4['title'], 'UnknownType #303 未知类型对象') !== false;
r($unknownTypeTitle) && p() && e('1'); // 测试未知类型时的标题格式
