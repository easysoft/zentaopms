#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getBugCardMenu();
timeout=0
cid=16979

- 步骤1：空数组输入 @0
- 步骤2：单个Bug测试 @1
- 步骤3：多个Bug测试 @3
- 步骤4：不同状态Bug @0
- 步骤5：权限测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('bug')->loadYaml('bug_getbugcardmenu', true, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getBugCardMenuTest(array())) && p() && e('0'); // 步骤1：空数组输入
r($kanbanTest->getBugCardMenuTest('singleBug')) && p() && e('1'); // 步骤2：单个Bug测试
r($kanbanTest->getBugCardMenuTest('multipleBugs')) && p() && e('3'); // 步骤3：多个Bug测试
r($kanbanTest->getBugCardMenuTest('bugWithDifferentStatus')) && p() && e('0'); // 步骤4：不同状态Bug
r($kanbanTest->getBugCardMenuTest('permissionTest')) && p() && e('1'); // 步骤5：权限测试