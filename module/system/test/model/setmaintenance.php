#!/usr/bin/env php
<?php

/**

title=测试 systemModel::setMaintenance();
timeout=0
cid=18746

- 步骤1：有效的backup操作 @1
- 步骤2：有效的restore操作 @1
- 步骤3：有效的upgrade操作 @1
- 步骤4：空字符串参数 @0
- 步骤5：无效操作参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$systemTest = new systemModelTest();

// 4. 必须包含至少5个测试步骤
r($systemTest->setMaintenanceTest('backup')) && p() && e('1');       // 步骤1：有效的backup操作
r($systemTest->setMaintenanceTest('restore')) && p() && e('1');      // 步骤2：有效的restore操作
r($systemTest->setMaintenanceTest('upgrade')) && p() && e('1');      // 步骤3：有效的upgrade操作
r($systemTest->setMaintenanceTest('')) && p() && e('0');             // 步骤4：空字符串参数
r($systemTest->setMaintenanceTest('invalid')) && p() && e('0');      // 步骤5：无效操作参数