#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printRecentProjectBlock();
timeout=0
cid=15281

- 步骤1：正常调用测试属性success @1
- 步骤2：检查是否有项目数据属性hasProjects @1
- 步骤3：检查返回项目数量（可能为0）属性projectCount @0
- 步骤4：验证无错误发生属性error @~~
- 步骤5：检查项目名称属性第projects条的0:name属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->code->range('proj1,proj2,proj3,proj4,proj5');
$project->type->range('project{3},sprint{2}');
$project->status->range('wait{1},doing{2},done{1},closed{1}');
$project->deleted->range('0');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printRecentProjectBlockTest()) && p('success') && e('1'); // 步骤1：正常调用测试
r($blockTest->printRecentProjectBlockTest()) && p('hasProjects') && e('1'); // 步骤2：检查是否有项目数据
r($blockTest->printRecentProjectBlockTest()) && p('projectCount') && e('0'); // 步骤3：检查返回项目数量（可能为0）
r($blockTest->printRecentProjectBlockTest()) && p('error') && e('~~'); // 步骤4：验证无错误发生
r($blockTest->printRecentProjectBlockTest()) && p('projects:0:name') && e('~~'); // 步骤5：检查项目名称属性