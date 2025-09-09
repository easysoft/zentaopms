#!/usr/bin/env php
<?php

/**

title=测试 screenModel::checkAccess();
timeout=0
cid=0

- 步骤1：正常有权限访问的screenID @0
- 步骤2：不存在的screenID @0
- 步骤3：超出范围的screenID @0
- 步骤4：边界值screenID为0 @0
- 步骤5：负数screenID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('screen');
$table->id->range('1-5');
$table->name->range('大屏1,大屏2,大屏3,大屏4,大屏5');
$table->dimension->range('1-3:R');
$table->status->range('published');
$table->deleted->range('0');
$table->createdBy->range('admin');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）  
$screenTest = new screenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->checkAccessTest(1)) && p() && e('0'); // 步骤1：正常有权限访问的screenID
r($screenTest->checkAccessTest(999)) && p() && e('0'); // 步骤2：不存在的screenID  
r($screenTest->checkAccessTest(100)) && p() && e('0'); // 步骤3：超出范围的screenID
r($screenTest->checkAccessTest(0)) && p() && e('0'); // 步骤4：边界值screenID为0
r($screenTest->checkAccessTest(-1)) && p() && e('0'); // 步骤5：负数screenID