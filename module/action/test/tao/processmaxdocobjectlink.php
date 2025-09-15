#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processMaxDocObjectLink();
timeout=0
cid=0

- 步骤1：处理practice类型doc
 - 属性moduleName @assetlib
 - 属性methodName @practiceView
- 步骤2：处理component类型doc
 - 属性moduleName @assetlib
 - 属性methodName @componentView
- 步骤3：处理空assetLibType的doc
 - 属性moduleName @doc
 - 属性methodName @view
- 步骤4：处理非doc类型且有配置
 - 属性moduleName @assetlib
 - 属性methodName @taskView
- 步骤5：处理不存在的doc
 - 属性moduleName @doc
 - 属性methodName @view

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$docTable = zenData('doc');
$docTable->id->range('1-10');
$docTable->assetLibType->range('practice,component,practice,component,[]{6}');
$docTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->processMaxDocObjectLinkTest(1, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,practiceView'); // 步骤1：处理practice类型doc
r($actionTest->processMaxDocObjectLinkTest(2, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,componentView'); // 步骤2：处理component类型doc
r($actionTest->processMaxDocObjectLinkTest(5, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view'); // 步骤3：处理空assetLibType的doc
r($actionTest->processMaxDocObjectLinkTest(1, 'task', 'view', 'taskID=%s')) && p('moduleName,methodName') && e('assetlib,taskView'); // 步骤4：处理非doc类型且有配置
r($actionTest->processMaxDocObjectLinkTest(999, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view'); // 步骤5：处理不存在的doc