#!/usr/bin/env php
<?php

/**

title=测试 bugZen::setEditMenu();
timeout=0
cid=15479

- 步骤1：project tab情况 @1
- 步骤2：execution tab情况 @1
- 步骤3：qa tab情况 @1
- 步骤4：devops tab情况 @1
- 步骤5：无效tab情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug')->gen(10);
zenData('product')->gen(3);
zenData('repo')->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->setEditMenuTest(1, 'project')) && p() && e('1'); // 步骤1：project tab情况
r($bugTest->setEditMenuTest(2, 'execution')) && p() && e('1'); // 步骤2：execution tab情况
r($bugTest->setEditMenuTest(3, 'qa')) && p() && e('1'); // 步骤3：qa tab情况
r($bugTest->setEditMenuTest(1, 'devops')) && p() && e('1'); // 步骤4：devops tab情况
r($bugTest->setEditMenuTest(2, 'invalid')) && p() && e('1'); // 步骤5：无效tab情况
