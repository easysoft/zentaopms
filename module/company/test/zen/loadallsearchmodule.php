#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadAllSearchModule();
timeout=0
cid=15737

- 步骤1:传入有效用户ID 1和queryID 0 @admin
- 步骤2:传入用户ID 0和queryID 0 @all
- 步骤3:传入有效用户ID 2和queryID 5 @user1
- 步骤4:传入有效用户ID 3和queryID 10 @user2
- 步骤5:传入不存在的用户ID 999和queryID 0 @all

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($companyTest->loadAllSearchModuleTest(1, 0)) && p() && e('admin'); // 步骤1:传入有效用户ID 1和queryID 0
r($companyTest->loadAllSearchModuleTest(0, 0)) && p() && e('all'); // 步骤2:传入用户ID 0和queryID 0
r($companyTest->loadAllSearchModuleTest(2, 5)) && p() && e('user1'); // 步骤3:传入有效用户ID 2和queryID 5
r($companyTest->loadAllSearchModuleTest(3, 10)) && p() && e('user2'); // 步骤4:传入有效用户ID 3和queryID 10
r($companyTest->loadAllSearchModuleTest(999, 0)) && p() && e('all'); // 步骤5:传入不存在的用户ID 999和queryID 0