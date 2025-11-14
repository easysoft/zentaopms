#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getObjectTypeTeamParams();
timeout=0
cid=14907

- 步骤1：action对象有project属性 @project
- 步骤2：action对象有execution属性 @execution
- 步骤3：两个属性都有（验证project优先级） @project
- 步骤4：action对象project和execution都为null @~~
- 步骤5：action对象属性为0（验证空值处理） @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 4. 强制要求：必须包含至少5个测试步骤
$action1 = new stdClass();
$action1->project = 10;
$action1->execution = null;
r($actionTest->getObjectTypeTeamParamsTest($action1)) && p('0') && e('project'); // 步骤1：action对象有project属性

$action2 = new stdClass();
$action2->project = null;
$action2->execution = 20;
r($actionTest->getObjectTypeTeamParamsTest($action2)) && p('0') && e('execution'); // 步骤2：action对象有execution属性

$action3 = new stdClass();
$action3->project = 10;
$action3->execution = 20;
r($actionTest->getObjectTypeTeamParamsTest($action3)) && p('0') && e('project'); // 步骤3：两个属性都有（验证project优先级）

$action4 = new stdClass();
$action4->project = null;
$action4->execution = null;
r($actionTest->getObjectTypeTeamParamsTest($action4)) && p('0') && e('~~'); // 步骤4：action对象project和execution都为null

$action5 = new stdClass();
$action5->project = 0;
$action5->execution = 0;
r($actionTest->getObjectTypeTeamParamsTest($action5)) && p('0') && e('~~'); // 步骤5：action对象属性为0（验证空值处理）