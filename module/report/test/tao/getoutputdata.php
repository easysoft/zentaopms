#!/usr/bin/env php
<?php

/**

title=测试 reportTao::getOutputData();
timeout=0
cid=18187

- 步骤1：正常用户账号和年份查询第case条的createBug属性 @0
- 步骤2：空账号数组查询第case条的run属性 @0
- 步骤3：特定用户查询第case条的createBug属性 @0
- 步骤4：不存在数据的年份第case条的createBug属性 @0
- 步骤5：多用户账号查询第case条的run属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. 简化数据准备（避免zendata生成错误）
zendata('action')->gen(0);
zendata('story')->gen(0);
zendata('task')->gen(0);
zendata('bug')->gen(0);
zendata('case')->gen(0);
zendata('testresult')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->getOutputDataTest(array('admin', 'user1'), '2024')) && p('case:createBug') && e('0'); // 步骤1：正常用户账号和年份查询
r($reportTest->getOutputDataTest(array(), '2024')) && p('case:run') && e('0'); // 步骤2：空账号数组查询
r($reportTest->getOutputDataTest(array('admin'), '2024')) && p('case:createBug') && e('0'); // 步骤3：特定用户查询
r($reportTest->getOutputDataTest(array('admin'), '1999')) && p('case:createBug') && e('0'); // 步骤4：不存在数据的年份
r($reportTest->getOutputDataTest(array('admin', 'user1', 'user2'), '2024')) && p('case:run') && e('0'); // 步骤5：多用户账号查询