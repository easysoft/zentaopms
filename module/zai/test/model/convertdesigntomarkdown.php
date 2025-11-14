#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertDesignToMarkdown();
timeout=0
cid=19764

- 测试转换完整的设计对象 @1
- 测试转换第二个设计对象 @2
- 测试验证返回了attrs属性 @1
- 测试验证第二个对象返回了attrs属性 @1
- 测试验证生成了content @1
- 测试验证第二个对象生成了content @1
- 测试验证生成了title @1
- 测试验证第二个对象生成了title @1
- 测试返回数组结构正确 @1
- 测试第二个对象返回数组结构正确 @1
- 测试验证产品属性 @1
- 测试验证类型属性 @HLDS


*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('design')->gen(0);
zenData('designspec')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

// 创建完整的设计对象
$design1 = new stdClass();
$design1->id = 1;
$design1->name = '测试设计1 - 用户界面设计';
$design1->type = 'HLDS';
$design1->product = 1;
$design1->project = 1;
$design1->story = 1;
$design1->version = 1;
$design1->assignedTo = 'designer1';
$design1->createdBy = 'admin';
$design1->createdDate = '2023-01-01 10:00:00';
$design1->execution = 1;

// 创建没有spec的设计对象
$design2 = new stdClass();
$design2->id = 2;
$design2->name = '测试设计2 - 数据库设计';
$design2->type = 'DDS';
$design2->product = 1;
$design2->project = 1;
$design2->story = 2;
$design2->version = 1;
$design2->assignedTo = 'architect1';
$design2->createdBy = 'admin';
$design2->createdDate = '2023-01-02 14:00:00';
$design2->execution = 1;

/* 测试转换设计对象 */
$result1 = $zai->convertDesignToMarkdownTest($design1);
r($result1) && p('id') && e('1'); // 测试转换完整的设计对象

$result2 = $zai->convertDesignToMarkdownTest($design2);
r($result2) && p('id') && e('2'); // 测试转换第二个设计对象

/* 测试验证基本属性 */
r(isset($result1['attrs'])) && p() && e('1'); // 测试验证返回了attrs属性
r(isset($result2['attrs'])) && p() && e('1'); // 测试验证第二个对象返回了attrs属性

/* 测试验证内容生成 */
r(isset($result1['content']) && !empty($result1['content'])) && p() && e('1'); // 测试验证生成了content
r(isset($result2['content']) && !empty($result2['content'])) && p() && e('1'); // 测试验证第二个对象生成了content

/* 测试验证标题生成 */
r(isset($result1['title']) && !empty($result1['title'])) && p() && e('1'); // 测试验证生成了title
r(isset($result2['title']) && !empty($result2['title'])) && p() && e('1'); // 测试验证第二个对象生成了title

/* 验证返回数组结构正确 */
r(is_array($result1)) && p() && e('1'); // 测试返回数组结构
r(is_array($result2)) && p() && e('1'); // 测试第二个对象返回数组结构

/* 验证具体的属性值 */
r($result1['attrs']['product']) && p() && e('1'); // 测试验证产品属性
r($result1['attrs']['type']) && p() && e('HLDS'); // 测试验证类型属性
