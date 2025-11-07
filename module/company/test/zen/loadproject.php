#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadProject();
timeout=0
cid=0

- 步骤1:正常情况下加载项目列表,验证返回类型为数组 @1
- 步骤2:验证返回数组不为空 @1
- 步骤3:验证返回数组包含项目ID 1 @1
- 步骤4:验证返回数组中项目1的名称属性1 @项目1
- 步骤5:验证返回数组中项目2的名称属性2 @项目2

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project');
$project->status->range('wait{5},doing{5}');
$project->parent->range('0');
$project->grade->range('1');
$project->deleted->range('0');
$project->vision->range('rnd');
$project->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r(is_array($companyTest->loadProjectTest())) && p() && e('1'); // 步骤1:正常情况下加载项目列表,验证返回类型为数组
r(count($companyTest->loadProjectTest()) > 0) && p() && e('1'); // 步骤2:验证返回数组不为空
r(isset($companyTest->loadProjectTest()[1])) && p() && e('1'); // 步骤3:验证返回数组包含项目ID 1
r($companyTest->loadProjectTest()) && p('1') && e('项目1'); // 步骤4:验证返回数组中项目1的名称
r($companyTest->loadProjectTest()) && p('2') && e('项目2'); // 步骤5:验证返回数组中项目2的名称