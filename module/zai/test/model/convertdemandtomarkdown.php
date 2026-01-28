#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertDemandToMarkdown();
timeout=0
cid=19763

- 测试转换完整的需求对象 @1
- 测试转换第二个需求对象 @2
- 测试验证返回了attrs属性 @1
- 测试验证第二个对象返回了attrs属性 @1
- 测试验证生成了content @1
- 测试验证第二个对象生成了content @1
- 测试验证生成了title @1
- 测试验证第二个对象生成了title @1
- 测试返回数组结构正确 @1
- 测试第二个对象返回数组结构正确 @1
- 测试验证产品属性 @1
- 测试验证状态属性 @active

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('demand')->gen(0);
zenData('demandspec')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

// 创建完整的需求对象
$demand1 = new stdClass();
$demand1->id = 1;
$demand1->title = '测试需求1 - 用户管理功能';
$demand1->status = 'active';
$demand1->stage = 'planned';
$demand1->pri = 3;
$demand1->version = 1;
$demand1->category = 'feature';
$demand1->source = 'customer';
$demand1->product = 1;
$demand1->parent = 0;
$demand1->module = 1;
$demand1->keywords = '用户,管理,功能';
$demand1->assignedTo = 'admin';
$demand1->assignedDate = '2023-01-01 10:00:00';
$demand1->createdBy = 'admin';
$demand1->createdDate = '2023-01-01 09:00:00';
$demand1->changedBy = '';
$demand1->changedDate = '';
$demand1->closedBy = '';
$demand1->closedDate = '';
$demand1->closedReason = '';
$demand1->submitedBy = 'admin';
$demand1->distributedBy = '';
$demand1->distributedDate = '';

// 创建没有spec的需求对象
$demand2 = new stdClass();
$demand2->id = 2;
$demand2->title = '测试需求2 - 报表功能';
$demand2->status = 'draft';
$demand2->stage = 'wait';
$demand2->pri = 2;
$demand2->version = 1;
$demand2->category = 'interface';
$demand2->source = 'po';
$demand2->product = 1;
$demand2->parent = 0;
$demand2->module = 2;
$demand2->keywords = '报表,统计';
$demand2->assignedTo = '';
$demand2->assignedDate = '';
$demand2->createdBy = 'admin';
$demand2->createdDate = '2023-01-03 09:00:00';
$demand2->changedBy = '';
$demand2->changedDate = '';
$demand2->closedBy = '';
$demand2->closedDate = '';
$demand2->closedReason = '';
$demand2->submitedBy = 'admin';
$demand2->distributedBy = '';
$demand2->distributedDate = '';

/* 测试转换需求对象 */
$result1 = $zai->convertDemandToMarkdownTest($demand1);
r($result1) && p('id') && e('1'); // 测试转换完整的需求对象

$result2 = $zai->convertDemandToMarkdownTest($demand2);
r($result2) && p('id') && e('2'); // 测试转换第二个需求对象

/* 测试验证基本属性 */
r(isset($result1['attrs'])) && p() && e('1'); // 测试验证返回了attrs属性
r(isset($result2['attrs'])) && p() && e('1'); // 测试验证第二个对象返回了attrs属性

/* 测试验证内容生成 */
r(isset($result1['content']) && !empty($result1['content'])) && p() && e('1'); // 测试验证生成了content
r(isset($result2['content'])) && p() && e('1'); // 测试验证第二个对象生成了content

/* 测试验证标题生成 */
r(isset($result1['title']) && !empty($result1['title'])) && p() && e('1'); // 测试验证生成了title
r(isset($result2['title'])) && p() && e('1'); // 测试验证第二个对象生成了title

/* 验证返回数组结构正确 */
r(is_array($result1)) && p() && e('1'); // 测试返回数组结构
r(is_array($result2)) && p() && e('1'); // 测试第二个对象返回数组结构正确

/* 验证具体的属性值 */
r($result1['attrs']['product']) && p() && e('1'); // 测试验证产品属性
r($result1['attrs']['status']) && p() && e('active'); // 测试验证状态属性
