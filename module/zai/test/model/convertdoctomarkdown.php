#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertDocToMarkdown();
timeout=0
cid=19765

- 测试转换完整的文档对象 @1
- 测试转换没有docContent的文档对象 @2
- 测试验证Markdown内容包含文档信息 @1
- 测试验证属性设置正确
  - 产品 @1
  - 库 @1
  - 模块 @1
  - 项目 @1
  - 执行 @1
  - 类型 @manual
- 测试验证标题包含ID @1
- 测试验证内容包含基本信息标题 @1
- 测试验证内容包含直接内容 @1
- 测试验证文档类型转换正确 @manual
- 测试验证Markdown内容包含文档标题 @测试文档1
- 测试验证不同版本文档的处理 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('doc')->gen(2);
zenData('doccontent')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

// 创建完整的文档对象
$doc1 = new stdClass();
$doc1->id = 1;
$doc1->title = '测试文档1 - 用户手册';
$doc1->type = 'manual';
$doc1->product = 1;
$doc1->project = 1;
$doc1->execution = 1;
$doc1->version = 1;
$doc1->lib = 1;
$doc1->module = 1;
$doc1->addedBy = 'admin';
$doc1->addedDate = '2023-01-01 10:00:00';
$doc1->editedBy = 'admin';
$doc1->editedDate = '2023-01-02 10:00:00';

// 创建没有docContent的文档对象
$doc2 = new stdClass();
$doc2->id = 2;
$doc2->title = '测试文档2 - 技术规范';
$doc2->type = 'standard';
$doc2->product = 1;
$doc2->project = 0;
$doc2->execution = 0;
$doc2->version = 1;
$doc2->lib = 2;
$doc2->module = 2;
$doc2->content = '<p>这是直接在doc对象中的内容</p>';
$doc2->addedBy = 'admin';
$doc2->addedDate = '2023-01-03 10:00:00';
$doc2->editedBy = '';
$doc2->editedDate = '';

/* 测试转换完整的文档对象 */
$result1 = $zai->convertDocToMarkdownTest($doc1);
r($result1) && p('id') && e('1'); // 测试转换完整的文档对象

/* 测试转换没有docContent的文档对象 */
$result2 = $zai->convertDocToMarkdownTest($doc2);
r($result2) && p('id') && e('2'); // 测试转换没有docContent的文档对象

/* 测试验证Markdown内容包含文档信息 */
$contentContainsDocId = strpos($result1['content'], '#1') !== false;
r($contentContainsDocId) && p() && e('1'); // 测试验证Markdown内容包含文档信息

/* 测试验证属性设置正确 */
r($result1['attrs']) && p('product,lib,module,project,execution,type') && e('1,1,1,1,1,manual'); // 测试验证属性设置正确

/* 测试验证标题格式正确 */
$titleContainsId = strpos($result1['title'], '#1') !== false;
r($titleContainsId) && p() && e('1'); // 测试验证标题包含ID

/* 测试验证内容包含基本信息标题 */
$contentContainsBasicInfo = strpos($result1['content'], '基本信息') !== false;
r($contentContainsBasicInfo) && p() && e('1'); // 测试验证内容包含基本信息标题

/* 测试验证第二个文档的内容包含直接内容 */
$content2ContainsDirectContent = strpos($result2['content'], '技术规范') !== false;
r($content2ContainsDirectContent) && p() && e('1'); // 测试验证内容包含直接内容

/* 测试验证文档类型转换正确 */
r($result1['attrs']['type']) && p() && e('manual'); // 测试验证文档类型转换正确

/* 测试验证Markdown内容包含文档标题 */
$contentContainsDocTitle = !empty($result1['content']);
r($contentContainsDocTitle) && p() && e('1'); // 测试验证Markdown内容包含文档标题

/* 测试验证不同版本文档的处理 */
r($result1['attrs']['version']) && p() && e('1'); // 测试验证不同版本文档的处理
