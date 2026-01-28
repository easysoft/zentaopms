#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getColumns();
timeout=0
cid=19416

- 步骤1：正常获取看板列，验证返回数组包含3个对象类型 @3
- 步骤2：验证story类型第一列的类型为backlog @1
- 步骤3：验证task类型第一列的类型为wait @1
- 步骤4：验证bug类型第一列的类型为unconfirmed @1
- 步骤5：验证story和task类型第一列的限制设置都为-1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getColumnsTest())) && p() && e('3'); // 步骤1：正常获取看板列，验证返回数组包含3个对象类型
r($tutorialTest->getColumnsTest()[1][0]['type'] == 'backlog') && p() && e('1'); // 步骤2：验证story类型第一列的类型为backlog
r($tutorialTest->getColumnsTest()[2][0]['type'] == 'wait') && p() && e('1'); // 步骤3：验证task类型第一列的类型为wait
r($tutorialTest->getColumnsTest()[3][0]['type'] == 'unconfirmed') && p() && e('1'); // 步骤4：验证bug类型第一列的类型为unconfirmed
r($tutorialTest->getColumnsTest()[1][0]['limit'] == -1 && $tutorialTest->getColumnsTest()[2][0]['limit'] == -1) && p() && e('1'); // 步骤5：验证story和task类型第一列的限制设置都为-1