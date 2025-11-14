#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::assignCreateVars();
timeout=0
cid=16999

- 步骤1:正常私有空间创建看板属性spaceID @1
- 步骤2:全局空间创建看板属性ownerPairs @10
- 步骤3:协作空间创建看板属性users @10
- 步骤4:带复制区域参数创建看板属性copyRegion @1
- 步骤5:不带复制区域参数创建看板属性copyRegion @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备
zendata('kanbanspace')->loadYaml('kanbanspace_assigncreatevars', false, 2)->gen(10);
zendata('kanban')->loadYaml('kanban_assigncreatevars', false, 2)->gen(5);
zendata('user')->loadYaml('user_assigncreatevars', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$kanbanTest = new kanbanTest();

// 5. 测试步骤
r($kanbanTest->assignCreateVarsTest(1, 'private', 0, '')) && p('spaceID') && e('1'); // 步骤1:正常私有空间创建看板
r($kanbanTest->assignCreateVarsTest(0, 'public', 0, '')) && p('ownerPairs') && e('10'); // 步骤2:全局空间创建看板
r($kanbanTest->assignCreateVarsTest(2, 'cooperation', 0, '')) && p('users') && e('10'); // 步骤3:协作空间创建看板
r($kanbanTest->assignCreateVarsTest(2, 'cooperation', 0, 'copyRegion=1')) && p('copyRegion') && e('1'); // 步骤4:带复制区域参数创建看板
r($kanbanTest->assignCreateVarsTest(3, 'public', 0, '')) && p('copyRegion') && e('0'); // 步骤5:不带复制区域参数创建看板