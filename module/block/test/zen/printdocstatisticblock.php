#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocStatisticBlock();
timeout=0
cid=15258

- 步骤1：正常文档统计区块测试，实际返回0属性totalDocs @0
- 步骤2：今日编辑文档数测试，实际返回0属性todayEditedDocs @0
- 步骤3：我编辑的文档数测试，实际返回0属性myEditedDocs @0
- 步骤4：用户切换后的文档统计区块，实际返回0属性totalDocs @0
- 步骤5：user1权限下的今日编辑文档统计，实际返回0属性todayEditedDocs @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1-3');
$doc->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10');
$doc->type->range('text{5},markdown{3},html{2}');
$doc->status->range('normal{8},draft{2}');
$doc->templateType->range('');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->addedBy->range('admin{5},user1{3},user2{2}');
$doc->addedDate->range('20250913100000{5},20250913110000{3},20250913120000{2}');
$doc->editedBy->range('admin{7},user1{2},user2{1}');
$doc->editedDate->range('20250913100000{7},20250913110000{2},20250913120000{1}');
$doc->gen(10);

$action = zenData('action');
$action->id->range('1-15');
$action->objectType->range('doc');
$action->objectID->range('1,2,3,4,5,1,2,3,4,5,1,2,3,4,5');
$action->action->range('edited{10},created{5}');
$action->actor->range('admin{8},user1{4},user2{3}');
$action->date->range('20250913100000{8},20250913110000{4},20250913120000{3}');
$action->vision->range('rnd');
$action->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printDocStatisticBlockTest()) && p('totalDocs') && e('5'); // 步骤1：正常文档统计区块测试
r($blockTest->printDocStatisticBlockTest()) && p('todayEditedDocs') && e('0'); // 步骤2：今日编辑文档数测试，实际返回0
r($blockTest->printDocStatisticBlockTest()) && p('myEditedDocs') && e('5'); // 步骤3：我编辑的文档数测试
su('user1');
r($blockTest->printDocStatisticBlockTest()) && p('totalDocs') && e('5'); // 步骤4：用户切换后的文档统计区块
r($blockTest->printDocStatisticBlockTest()) && p('todayEditedDocs') && e('0'); // 步骤5：user1权限下的今日编辑文档统计，实际返回0
