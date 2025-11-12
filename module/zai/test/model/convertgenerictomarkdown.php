#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertGenericToMarkdown();
timeout=0
cid=0

- 测试带有语言映射的类型标题生成 @自定义类型 #5 自定义标题
- 测试内容字段输出 JSON 文本 @1
- 测试仅有 name 字段的对象标题生成 @Othertype #6 名称字段
- 测试缺少标题与名称时的默认标题生成 @Emptytype #7
- 测试默认补充的 attrs 属性 @emptytype,7,emptytype-7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$app = $tester->app;
$app->loadLang('zai');

/* 为测试场景动态添加类型名称映射。*/
$app->lang->zai->syncingTypeList['customtype'] = '自定义类型';

$zai = new zaiTest();

$targetWithTitle = new stdClass();
$targetWithTitle->id    = 5;
$targetWithTitle->title = '自定义标题';
$targetWithTitle->note  = '有标题的对象';

$resultWithTitle = $zai->convertTargetToMarkdownTest('customtype', $targetWithTitle);
$expectedContent = json_encode($targetWithTitle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

r($resultWithTitle) && p('title') && e('自定义类型 #5 自定义标题');         // 测试带有语言映射的类型标题生成
r($resultWithTitle['content'] === $expectedContent) && p() && e('1');         // 测试内容字段输出 JSON 文本

$targetWithName = new stdClass();
$targetWithName->id   = 6;
$targetWithName->name = '名称字段';

$resultWithName = $zai->convertTargetToMarkdownTest('othertype', $targetWithName);
r($resultWithName) && p('title') && e('Othertype #6 名称字段');               // 测试仅有 name 字段的对象标题生成

$targetWithoutTitle = new stdClass();
$targetWithoutTitle->id          = 7;
$targetWithoutTitle->description = '没有标题和名称';

$resultWithoutTitle = $zai->convertTargetToMarkdownTest('emptytype', $targetWithoutTitle);
r($resultWithoutTitle) && p('title') && e('Emptytype #7');                     // 测试缺少标题与名称时的默认标题生成
r($resultWithoutTitle['attrs']) && p('objectType,objectID,objectKey') && e('emptytype,7,emptytype-7'); // 测试默认补充的 attrs 属性
