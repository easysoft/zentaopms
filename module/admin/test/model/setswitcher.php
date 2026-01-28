#!/usr/bin/env php
<?php

/**

title=测试 adminModel::setSwitcher();
timeout=0
cid=14984

- 步骤1：正常情况，system菜单 @success
- 步骤2：正常情况，company菜单 @success
- 步骤3：正常情况，feature菜单 @success
- 步骤4：空参数情况 @0
- 步骤5：不存在菜单键测试异常处理 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$adminTest = new adminModelTest();

r($adminTest->setSwitcherTest('system')) && p() && e('success'); // 步骤1：正常情况，system菜单
r($adminTest->setSwitcherTest('company')) && p() && e('success'); // 步骤2：正常情况，company菜单
r($adminTest->setSwitcherTest('feature')) && p() && e('success'); // 步骤3：正常情况，feature菜单
r($adminTest->setSwitcherTest('')) && p() && e('0'); // 步骤4：空参数情况
r($adminTest->setSwitcherTest('nonexistent')) && p() && e('success'); // 步骤5：不存在菜单键测试异常处理
