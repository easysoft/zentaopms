#!/usr/bin/env php
<?php

/**

title=测试 actionZen::getReplaceNameAndCode();
timeout=0
cid=0

- 步骤1：正常情况下获取重复名称和代号的替换方案
 -  @项目A_3
 - 属性1 @PROJA_3
- 步骤2：只有名称重复，代号为空的情况 @项目B_1
- 步骤3：只有代号重复的情况属性1 @PROJB_1
- 步骤4：名称和代号都不重复的情况 @全新项目_1
- 步骤5：存在一个重复名称时的情况
 -  @项目C_1
 - 属性1 @PROJC_1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->name->range('项目A,项目B,项目A_1,项目A_2,项目C');
$table->code->range('PROJA,PROJB,PROJA_1,PROJA_2,PROJC');
$table->deleted->range('0');
$table->gen(5);

$actionTable = zenData('action');
$actionTable->objectType->range('project');
$actionTable->objectID->range('1-5');
$actionTable->action->range('deleted');
$actionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->getReplaceNameAndCodeTest('项目A', 'PROJA', TABLE_PROJECT)) && p('0,1') && e('项目A_3,PROJA_3'); // 步骤1：正常情况下获取重复名称和代号的替换方案
r($actionTest->getReplaceNameAndCodeTest('项目B', '', TABLE_PROJECT)) && p('0') && e('项目B_1'); // 步骤2：只有名称重复，代号为空的情况
r($actionTest->getReplaceNameAndCodeTest('新项目', 'PROJB', TABLE_PROJECT)) && p('1') && e('PROJB_1'); // 步骤3：只有代号重复的情况  
r($actionTest->getReplaceNameAndCodeTest('全新项目', 'NEWPROJ', TABLE_PROJECT)) && p('0') && e('全新项目_1'); // 步骤4：名称和代号都不重复的情况
r($actionTest->getReplaceNameAndCodeTest('项目C', 'PROJC', TABLE_PROJECT)) && p('0,1') && e('项目C_1,PROJC_1'); // 步骤5：存在一个重复名称时的情况