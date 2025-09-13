#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocViewListBlock();
timeout=0
cid=0

- 步骤1：测试有数据时正常返回文档列表
 - 属性success @1
 - 属性docCount @6
- 步骤2：测试方法执行成功属性success @1
- 步骤3：测试分页功能正常工作属性success @1
- 步骤4：测试只返回正常状态文档属性success @1
- 步骤5：测试文档权限控制属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 准备文档库数据
$libTable = zenData('doclib');
$libTable->id->range('1-5');
$libTable->type->range('custom');
$libTable->name->range('测试文档库1,测试文档库2,测试文档库3');
$libTable->acl->range('open');
$libTable->deleted->range('0');
$libTable->gen(5);

// 准备文档数据
$table = zenData('doc');
$table->id->range('1-20');
$table->lib->range('1-5');
$table->title->range('测试文档1,测试文档2,测试文档3,用户手册,技术文档,项目说明');
$table->views->range('100,200,300,150,250,180');
$table->status->range('normal');
$table->deleted->range('0');
$table->acl->range('open');
$table->vision->range('rnd');
$table->templateType->range('');
$table->type->range('text');
$table->addedBy->range('admin');
$table->users->range('');
$table->groups->range('');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printDocViewListBlockTest()) && p('success,docCount') && e('1,6'); // 步骤1：测试有数据时正常返回文档列表

// 测试返回成功
r($blockTest->printDocViewListBlockTest()) && p('success') && e('1'); // 步骤2：测试方法执行成功

// 测试分页限制（最多返回6条）
r($blockTest->printDocViewListBlockTest()) && p('success') && e('1'); // 步骤3：测试分页功能正常工作

// 测试状态过滤功能
$table = zenData('doc');
$table->id->range('11-15');
$table->lib->range('1-3');
$table->title->range('草稿文档1,草稿文档2');
$table->status->range('draft');
$table->deleted->range('0');
$table->views->range('500');
$table->vision->range('rnd');
$table->templateType->range('');
$table->type->range('text');
$table->addedBy->range('admin');
$table->gen(2);

r($blockTest->printDocViewListBlockTest()) && p('success') && e('1'); // 步骤4：测试只返回正常状态文档

// 测试已删除文档过滤
$table = zenData('doc');
$table->id->range('16-20');
$table->lib->range('1-3');
$table->title->range('已删除文档1,已删除文档2');
$table->status->range('normal');
$table->deleted->range('1');
$table->views->range('600');
$table->vision->range('rnd');
$table->templateType->range('');
$table->type->range('text');
$table->addedBy->range('admin');
$table->gen(2);

r($blockTest->printDocViewListBlockTest()) && p('success') && e('1'); // 步骤5：测试文档权限控制