#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildSearchFormForTrack();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性success @1
 - 属性productID @1
 - 属性storyType @story
- 步骤2：项目需求环境
 - 属性success @1
 - 属性projectID @1
 - 属性searchModule @projectstoryTrack
- 步骤3：产品环境
 - 属性success @1
 - 属性actionURL @product/track
 - 属性queryID @0
- 步骤4：搜索模式
 - 属性success @1
 - 属性browseType @bysearch
 - 属性queryID @10
- 步骤5：项目产品
 - 属性success @1
 - 属性projectID @2
 - 属性searchModule @projectstoryTrack

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('0,1-3');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal{7},branch{3}');
$table->PO->range('admin,user1,user2');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->status->range('wait{2},doing{2},done{1}');
$projectTable->type->range('project');
$projectTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildSearchFormForTrackTest(1, 'main', 0, 'unclosed', 0, 'story')) && p('success,productID,storyType') && e('1,1,story'); // 步骤1：正常情况
r($productTest->buildSearchFormForTrackTest(2, 'dev', 1, 'bymodule', 5, 'requirement')) && p('success,projectID,searchModule') && e('1,1,projectstoryTrack'); // 步骤2：项目需求环境
r($productTest->buildSearchFormForTrackTest(0, '', 0, 'all', 0, 'epic')) && p('success,actionURL,queryID') && e('1,product/track,0'); // 步骤3：产品环境
r($productTest->buildSearchFormForTrackTest(3, 'test', 0, 'bysearch', 10, 'story')) && p('success,browseType,queryID') && e('1,bysearch,10'); // 步骤4：搜索模式
r($productTest->buildSearchFormForTrackTest(4, 'all', 2, 'byproject', 0, 'requirement')) && p('success,projectID,searchModule') && e('1,2,projectstoryTrack'); // 步骤5：项目产品