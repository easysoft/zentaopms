#!/usr/bin/env php
<?php

/**

title=测试 transferZen::printCell();
timeout=0
cid=0

- 步骤1：测试select控件 @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 步骤2：测试input控件 @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 步骤3：测试hidden控件 @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 步骤4：测试textarea控件 @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 步骤5：测试date控件 @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->printCellTest('task', 'status', 'select', 'status[1]', 'wait', array('wait' => '未开始', 'doing' => '进行中', 'done' => '已完成'), 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752'); // 步骤1：测试select控件
r($transferTest->printCellTest('task', 'name', 'input', 'name[1]', 'test task', array(), 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752'); // 步骤2：测试input控件
r($transferTest->printCellTest('task', 'execution', 'hidden', 'execution[1]', '5', array(), 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752'); // 步骤3：测试hidden控件
r($transferTest->printCellTest('bug', 'steps', 'textarea', 'steps[1]', 'test steps', array(), 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752'); // 步骤4：测试textarea控件
r($transferTest->printCellTest('task', 'deadline', 'date', 'deadline[1]', '2023-12-31', array(), 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752'); // 步骤5：测试date控件