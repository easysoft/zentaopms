#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtractPath();
timeout=0
cid=0

- 步骤1：使用已存在插件测试，返回对象类型 @1
- 步骤2：使用空插件名测试，返回对象类型 @1
- 步骤3：使用不存在插件测试，返回对象类型 @1
- 步骤4：检查返回对象的result属性属性result @fail
- 步骤5：验证对象结构完整性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）  
$table = zenData('extension');
$table->name->range('测试插件1,测试插件2,样例插件,演示插件,功能插件');
$table->code->range('testplugin1,testplugin2,sampleplugin,demoplugin,functionplugin');
$table->version->range('1.0.0,1.1.0,2.0.0,1.5.0,3.0.0');
$table->status->range('installed{2},available{3}');
$table->type->range('extension{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 创建初始检查结果对象
$checkResult = new stdClass();
$checkResult->result = 'ok';
$checkResult->errors = '';
$checkResult->mkdirCommands = '';
$checkResult->chmodCommands = '';
$checkResult->dirs2Created = array();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $extensionTest->checkExtractPathTest('testplugin1', $checkResult);
r(is_object($result1)) && p() && e('1'); // 步骤1：使用已存在插件测试，返回对象类型

$result2 = $extensionTest->checkExtractPathTest('', $checkResult);
r(is_object($result2)) && p() && e('1'); // 步骤2：使用空插件名测试，返回对象类型

$result3 = $extensionTest->checkExtractPathTest('nonexistent', $checkResult);
r(is_object($result3)) && p() && e('1'); // 步骤3：使用不存在插件测试，返回对象类型

r($extensionTest->checkExtractPathTest('testplugin1', $checkResult)) && p('result') && e('fail'); // 步骤4：检查返回对象的result属性

$result5 = $extensionTest->checkExtractPathTest('testplugin1', $checkResult);
r(isset($result5->errors) && isset($result5->mkdirCommands) && isset($result5->chmodCommands)) && p() && e('1'); // 步骤5：验证对象结构完整性