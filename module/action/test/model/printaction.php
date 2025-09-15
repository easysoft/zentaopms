#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printAction();
timeout=0
cid=0

- 步骤1：正常情况，实际输出内容由renderAction决定 @2023-01-01 12:00:00, 由 <strong>admin</strong> 创建。

- 步骤2：objectType未设置 @~~
- 步骤3：action字段未设置 @0
- 步骤4：自定义desc @0
- 步骤5：无效objectType仍会有输出 @自定义描述

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('action', false, 4)->gen(10);
zenData('user')->loadYaml('user', false, 4)->gen(10);

su('admin');

$actionTest = new actionTest();

// 创建基础action对象模板
function createBaseAction() {
    $action = new stdclass();
    $action->id = 1;
    $action->objectType = 'task';
    $action->objectID = 1;
    $action->action = 'created';
    $action->actor = 'admin';
    $action->date = '2023-01-01 12:00:00';
    $action->comment = '测试注释';
    $action->extra = '';
    $action->read = '0';
    $action->vision = 'rnd';
    $action->efforted = 0;
    return $action;
}

// 创建正常的action对象
$normalAction = createBaseAction();

// 创建objectType未设置的action对象
$emptyObjectTypeAction = createBaseAction();
unset($emptyObjectTypeAction->objectType);

// 创建action字段未设置的action对象
$emptyActionAction = createBaseAction();
unset($emptyActionAction->action);

// 创建用于测试自定义desc的action对象
$customDescAction = createBaseAction();

// 创建objectType不存在的action对象
$invalidAction = createBaseAction();
$invalidAction->objectType = 'invalid_type';

r($actionTest->printActionTest($normalAction)) && p() && e('2023-01-01 12:00:00, 由 <strong>admin</strong> 创建。'); // 步骤1：正常情况，实际输出内容由renderAction决定
r($actionTest->printActionTest($emptyObjectTypeAction)) && p() && e('~~'); // 步骤2：objectType未设置
r($actionTest->printActionTest($emptyActionAction)) && p() && e('0'); // 步骤3：action字段未设置
r($actionTest->printActionTest($customDescAction, '自定义描述')) && p() && e('0'); // 步骤4：自定义desc
r($actionTest->printActionTest($invalidAction)) && p() && e('自定义描述'); // 步骤5：无效objectType仍会有输出