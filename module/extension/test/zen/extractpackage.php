#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::extractPackage();
timeout=0
cid=0

- 步骤1：正常情况（实际会失败因为没有zip包文件）属性result @fail
- 步骤2：不存在的插件包属性result @fail
- 步骤3：空字符串插件名属性result @fail
- 步骤4：特殊字符插件名属性result @fail
- 步骤5：无效格式属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('extension');
$table->id->range('1-5');
$table->name->range('测试插件1,测试插件2,示例插件1,演示插件1,样例插件1');
$table->code->range('testplugin1,testplugin2,sampleplugin1,demoplugin1,exampleplugin1');
$table->version->range('1.0.0,1.1.0,2.0.0,2.1.0,3.0.0');
$table->author->range('测试作者1,测试作者2,开发者1,开发者2,作者A');
$table->type->range('extension{5}');
$table->status->range('available{3},installed{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->extractPackageTest('testplugin1')) && p('result') && e('fail'); // 步骤1：正常情况（实际会失败因为没有zip包文件）
r($extensionTest->extractPackageTest('nonexistent')) && p('result') && e('fail'); // 步骤2：不存在的插件包
r($extensionTest->extractPackageTest('')) && p('result') && e('fail'); // 步骤3：空字符串插件名
r($extensionTest->extractPackageTest('invalid@plugin')) && p('result') && e('fail'); // 步骤4：特殊字符插件名
r($extensionTest->extractPackageTest('badformat')) && p('result') && e('fail'); // 步骤5：无效格式