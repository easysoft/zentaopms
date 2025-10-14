#!/usr/bin/env php
<?php

/**

title=测试 docZen::getDocChildrenByRecursion();
timeout=0
cid=0

- 步骤1：层级为0时边界情况 @0
- 步骤2：层级为负数时边界情况 @0
- 步骤3：层级为1时获取直接子文档 @3
- 步骤4：层级为2时获取多级子文档 @3
- 步骤5：没有子文档的情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doc');
$table->id->range('1-20');
$table->product->range('1-20');
$table->project->range('0{20}');
$table->execution->range('0{20}');
$table->lib->range('1{20}');
$table->template->range('{20}');
$table->templateType->range('{20}');
$table->chapterType->range('article{20}');
$table->module->range('0{20}');
$table->title->range('文档1,文档2,文档3,文档4,文档5,子文档1,子文档2,子文档3,孙文档1,孙文档2,孙文档3,曾孙文档1,曾孙文档2,曾孙文档3,叶子文档1,叶子文档2,叶子文档3,叶子文档4,叶子文档5,叶子文档6');
$table->keywords->range('关键词1,关键词2,关键词3,关键词4,关键词5,关键词6,关键词7,关键词8,关键词9,关键词10,关键词11,关键词12,关键词13,关键词14,关键词15,关键词16,关键词17,关键词18,关键词19,关键词20');
$table->type->range('text{20}');
$table->parent->range('0,0,0,0,0,1,1,1,2,2,2,3,3,3,10,10,10,11,11,11');
$table->path->range('0{20}');
$table->grade->range('0{20}');
$table->order->range('0{20}');
$table->views->range('0{20}');
$table->addedBy->range('admin{20}');
$table->addedDate->range(date('Y-m-d H:i:s'));
$table->editedBy->range('admin{20}');
$table->editedDate->range(date('Y-m-d H:i:s'));
$table->acl->range('open{20}');
$table->groups->range('{20}');
$table->users->range('{20}');
$table->draft->range('{20}');
$table->version->range('1{20}');
$table->deleted->range('0{20}');
$table->status->range('normal{20}');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($docTest->getDocChildrenByRecursionTest(1, 0))) && p() && e('0'); // 步骤1：层级为0时边界情况
r(count($docTest->getDocChildrenByRecursionTest(1, -1))) && p() && e('0'); // 步骤2：层级为负数时边界情况
r(count($docTest->getDocChildrenByRecursionTest(1, 1))) && p() && e('3'); // 步骤3：层级为1时获取直接子文档
r(count($docTest->getDocChildrenByRecursionTest(1, 2))) && p() && e('3'); // 步骤4：层级为2时获取多级子文档
r(count($docTest->getDocChildrenByRecursionTest(15, 1))) && p() && e('0'); // 步骤5：没有子文档的情况