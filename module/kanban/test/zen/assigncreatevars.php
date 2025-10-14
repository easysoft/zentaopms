#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::assignCreateVars();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性spaceID @1
 - 属性type @private
 - 属性enableImport @on
- 步骤2：复制有导入对象的看板
 - 属性copyKanbanID @2
 - 属性enableImport @on
- 步骤3：复制有对象的看板
 - 属性copyKanbanID @2
 - 属性enableImport @on
- 步骤4：带额外参数
 - 属性copyRegion @1
 - 属性spaceID @1
 - 属性type @private
- 步骤5：私人空间类型验证属性type @private

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$spaceTable = zenData('kanbanspace');
$spaceTable->loadYaml('kanbanspace_assigncreatevars', false, 2)->gen(10);

$kanbanTable = zenData('kanban');
$kanbanTable->loadYaml('kanban_assigncreatevars', false, 2)->gen(5);

$userTable = zenData('user');
$userTable->loadYaml('user_assigncreatevars', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->assignCreateVarsTest(1, 'private', 0, '')) && p('spaceID,type,enableImport') && e('1,private,on'); // 步骤1：正常情况
r($kanbanTest->assignCreateVarsTest(2, 'cooperation', 2, '')) && p('copyKanbanID,enableImport') && e('2,on'); // 步骤2：复制有导入对象的看板
r($kanbanTest->assignCreateVarsTest(3, 'public', 2, '')) && p('copyKanbanID,enableImport') && e('2,on'); // 步骤3：复制有对象的看板
r($kanbanTest->assignCreateVarsTest(1, 'private', 0, 'copyRegion=1')) && p('copyRegion,spaceID,type') && e('1,1,private'); // 步骤4：带额外参数
r($kanbanTest->assignCreateVarsTest(1, 'private', 0, '')) && p('type') && e('private'); // 步骤5：私人空间类型验证