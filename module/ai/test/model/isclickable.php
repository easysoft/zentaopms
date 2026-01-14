#!/usr/bin/env php
<?php

/**

title=测试 aiModel::isClickable();
timeout=0
cid=15055

- 步骤1：正常情况，模型启用动作，对象disabled状态 @1
- 步骤2：边界值，模型启用动作，对象已enabled状态 @0
- 步骤3：异常输入，空对象和空动作 @0
- 步骤4：权限验证，助手发布动作，对象disabled状态 @1
- 步骤5：业务规则，助手撤回动作，对象enabled状态 @1
- 步骤6：助手发布动作，对象已enabled状态 @0
- 步骤7：助手撤回动作，对象已disabled状态 @0
- 步骤8：助手编辑动作，对象已enabled状态 @0
- 步骤9：助手编辑动作，对象disabled状态 @1
- 步骤10：未知动作，返回默认true @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->rawMethod = 'models';
// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 准备测试对象数据
$enabledObject = new stdClass();
$enabledObject->enabled = '1';

$disabledObject = new stdClass();
$disabledObject->enabled = '0';

$emptyObject = null;

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->isClickableTest($disabledObject, 'modelenable'))       && p() && e('1'); // 步骤1：正常情况，模型启用动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'modelenable'))        && p() && e('0'); // 步骤2：边界值，模型启用动作，对象已enabled状态
r($aiTest->isClickableTest($emptyObject, ''))                     && p() && e('0'); // 步骤3：异常输入，空对象和空动作
r($aiTest->isClickableTest($disabledObject, 'assistantpublish'))  && p() && e('1'); // 步骤4：权限验证，助手发布动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantwithdraw'))  && p() && e('1'); // 步骤5：业务规则，助手撤回动作，对象enabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantpublish'))   && p() && e('0'); // 步骤6：助手发布动作，对象已enabled状态
r($aiTest->isClickableTest($disabledObject, 'assistantwithdraw')) && p() && e('0'); // 步骤7：助手撤回动作，对象已disabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantedit'))      && p() && e('0'); // 步骤8：助手编辑动作，对象已enabled状态
r($aiTest->isClickableTest($disabledObject, 'assistantedit'))     && p() && e('1'); // 步骤9：助手编辑动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'unknownaction'))      && p() && e('1'); // 步骤10：未知动作，返回默认true
