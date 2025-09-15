#!/usr/bin/env php
<?php

/**

title=测试 programModel::getSwitcher();
timeout=0
cid=0

- 步骤1：有效项目集ID @/项目集1.*btn.*dropdown/
- 步骤2：ID为0的情况 @/全部.*btn.*dropdown/
- 步骤3：不存在的项目集ID @/全部.*btn.*dropdown/
- 步骤4：负数ID @/全部.*btn.*dropdown/
- 步骤5：大数值ID @/全部.*btn.*dropdown/

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-20');
$table->type->range('program{10},project{10}');
$table->name->range('项目集1,项目集2,项目集3,项目集4,项目集5,项目1,项目2,项目3,项目4,项目5,测试项目集1,测试项目集2,测试项目集3,测试项目集4,测试项目集5,开发项目集1,开发项目集2,开发项目集3,开发项目集4,开发项目集5');
$table->status->range('doing{8},wait{6},suspended{3},closed{3}');
$table->deleted->range('0{18},1{2}');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programTest = new programTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($programTest->getSwitcherTest(1)) && p() && e("*项目集1*"); // 步骤1：有效项目集ID
r($programTest->getSwitcherTest(0)) && p() && e("*全部*"); // 步骤2：ID为0的情况
r($programTest->getSwitcherTest(999)) && p() && e("*全部*"); // 步骤3：不存在的项目集ID
r($programTest->getSwitcherTest(-1)) && p() && e("*全部*"); // 步骤4：负数ID
r($programTest->getSwitcherTest(99999)) && p() && e("*全部*"); // 步骤5：大数值ID