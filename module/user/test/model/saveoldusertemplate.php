#!/usr/bin/env php
<?php

/**

title=测试 userModel::saveOldUserTemplate();
timeout=0
cid=19653

- 步骤1：正常保存Bug模板属性result @1
- 步骤2：正常保存Story模板属性result @1
- 步骤3：正常保存Task模板属性result @1
- 步骤4：保存空内容模板第errors条的content属性 @『模板内容』不能为空。
- 步骤5：保存空标题模板第errors条的title属性 @『模板名称』不能为空。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('usertpl');
$table->loadYaml('usertpl_saveoldusertemplate', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$_POST = array('title' => '新建Bug模板', 'content' => '这是一个新的Bug模板内容', 'public' => '0');
r($userTest->saveOldUserTemplateTest('bug')) && p('result') && e('1'); // 步骤1：正常保存Bug模板

$_POST = array('title' => '新建Story模板', 'content' => '这是一个新的Story模板内容', 'public' => '0');
r($userTest->saveOldUserTemplateTest('story')) && p('result') && e('1'); // 步骤2：正常保存Story模板

$_POST = array('title' => '新建Task模板', 'content' => '这是一个新的Task模板内容', 'public' => '1');
r($userTest->saveOldUserTemplateTest('task')) && p('result') && e('1'); // 步骤3：正常保存Task模板

$_POST = array('title' => '', 'content' => '', 'public' => '0');
r($userTest->saveOldUserTemplateTest('bug')) && p('errors:content') && e('『模板内容』不能为空。'); // 步骤4：保存空内容模板

$_POST = array('title' => '', 'content' => '没有标题的模板', 'public' => '0');
r($userTest->saveOldUserTemplateTest('bug')) && p('errors:title') && e('『模板名称』不能为空。'); // 步骤5：保存空标题模板