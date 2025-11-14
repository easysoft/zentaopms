#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getTrashObjectTypes();
timeout=0
cid=14913

- 步骤1：正常查询可恢复删除类型(extra=1) @0
- 步骤2：正常查询隐藏删除类型(extra=2)第testcase条的objectType属性 @testcase
- 步骤3：空字符串参数测试(默认为extra=1) @0
- 步骤4：无效类型参数测试(默认为extra=1) @0
- 步骤5：大小写混合类型参数测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备
zenData('action')->loadYaml('action_gettrashobjecttypes', false, 2)->gen(30);
zenData('actionrecent')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$action = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($action->getTrashObjectTypesTest('all'))     && p() && e('0');        // 步骤1：正常查询可恢复删除类型(extra=1)
r($action->getTrashObjectTypesTest('hidden'))  && p('testcase:objectType') && e('testcase'); // 步骤2：正常查询隐藏删除类型(extra=2)
r($action->getTrashObjectTypesTest(''))        && p() && e('0');        // 步骤3：空字符串参数测试(默认为extra=1)
r($action->getTrashObjectTypesTest('invalid')) && p() && e('0');        // 步骤4：无效类型参数测试(默认为extra=1)
r($action->getTrashObjectTypesTest('HIDDEN'))  && p() && e('0');        // 步骤5：大小写混合类型参数测试