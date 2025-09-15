#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtensionPaths();
timeout=0
cid=0

- 步骤1：正常插件名称属性result @fail
- 步骤2：空插件名称属性result @fail
- 步骤3：不存在插件属性result @fail
- 步骤4：特殊字符插件名属性result @fail
- 步骤5：检查errors属性属性errors @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('extension');
$table->name->range('测试插件1,测试插件2,样例插件,演示插件,功能插件1,功能插件2,功能插件3,功能插件4,功能插件5,核心插件');
$table->code->range('testplugin1,testplugin2,sampleplugin,demoplugin,functionplugin1,functionplugin2,functionplugin3,functionplugin4,functionplugin5,coreplugin');
$table->version->range('1.0.0,1.1.0,2.0.0,1.5.0,3.0.0,3.1.0,3.2.0,3.3.0,3.4.0,4.0.0');
$table->status->range('installed{3},available{2},disabled{5}');
$table->type->range('extension{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($extensionTest->checkExtensionPathsTest('testplugin1')) && p('result') && e('fail'); // 步骤1：正常插件名称
r($extensionTest->checkExtensionPathsTest('')) && p('result') && e('fail'); // 步骤2：空插件名称
r($extensionTest->checkExtensionPathsTest('nonexistent')) && p('result') && e('fail'); // 步骤3：不存在插件
r($extensionTest->checkExtensionPathsTest('plugin@#$%')) && p('result') && e('fail'); // 步骤4：特殊字符插件名
r($extensionTest->checkExtensionPathsTest('testplugin1')) && p('errors') && e('~~'); // 步骤5：检查errors属性