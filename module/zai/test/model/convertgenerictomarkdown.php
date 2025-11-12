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
