#!/usr/bin/env php
<?php

/**

title=测试 adminZen::certifyByAPI();
timeout=0
cid=0

- 步骤1：正常mobile类型认证 @1
- 步骤2：正常email类型认证 @1
- 步骤3：空字符串类型 @1
- 步骤4：非法类型参数 @1
- 步骤5：再次测试mobile类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. zendata数据准备（设置必要的配置数据）
// 由于网络API测试不依赖具体数据库数据，此处省略zendata配置

// 3. 用户登录（使用管理员权限）
su('admin');

// 4. 创建测试实例
$adminTest = new adminTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r($adminTest->certifyByAPITest('mobile')) && p() && e('1'); // 步骤1：正常mobile类型认证
r($adminTest->certifyByAPITest('email')) && p() && e('1'); // 步骤2：正常email类型认证
r($adminTest->certifyByAPITest('')) && p() && e('1'); // 步骤3：空字符串类型
r($adminTest->certifyByAPITest('invalid')) && p() && e('1'); // 步骤4：非法类型参数
r($adminTest->certifyByAPITest('mobile')) && p() && e('1'); // 步骤5：再次测试mobile类型