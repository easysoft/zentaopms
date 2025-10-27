#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::backupDB();
timeout=0
cid=0

- 步骤1：测试不存在卸载SQL文件的插件 @0
- 步骤2：测试不存在的插件名称 @0
- 步骤3：测试空插件名称 @0
- 步骤4：测试特殊字符插件名称 @0
- 步骤5：测试有效插件名但无SQL文件 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('extension');
$table->code->range('testext1,testext2,testext3,testext4,testext5');
$table->name->range('测试插件1,测试插件2,测试插件3,测试插件4,测试插件5');
$table->status->range('installed{3},available{2}');
$table->type->range('extension{5}');
$table->site->range('1{5}');
$table->dirs->range('[]');
$table->files->range('[]');
$table->depends->range('[]');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($extensionTest->backupDBTest('testext1')) && p() && e('0'); // 步骤1：测试不存在卸载SQL文件的插件
r($extensionTest->backupDBTest('nonexistent')) && p() && e('0'); // 步骤2：测试不存在的插件名称
r($extensionTest->backupDBTest('')) && p() && e('0'); // 步骤3：测试空插件名称
r($extensionTest->backupDBTest('test@#$%')) && p() && e('0'); // 步骤4：测试特殊字符插件名称
r($extensionTest->backupDBTest('testvalid')) && p() && e('0'); // 步骤5：测试有效插件名但无SQL文件