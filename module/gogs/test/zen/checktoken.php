#!/usr/bin/env php
<?php

/**

title=测试 gogsZen::checkToken();
timeout=0
cid=16693

- 步骤1：正常情况（会因网络连接失败）属性result @fail
- 步骤2：缺少name字段属性result @fail
- 步骤3：缺少url字段属性result @fail
- 步骤4：缺少token字段属性result @fail
- 步骤5：无效token权限属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gogs');
$table->name->range('TestGogs{5}');
$table->url->range('http://localhost:3000{5}');
$table->token->range('test_token_{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gogsTest = new gogsZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$validGogs = new stdclass();
$validGogs->name = 'TestGogs';
$validGogs->url = 'http://localhost:3000';
$validGogs->token = 'valid_token';
r($gogsTest->checkTokenTest($validGogs)) && p('result') && e('fail'); // 步骤1：正常情况（会因网络连接失败）

$missingName = new stdclass();
$missingName->name = '';  // 空name字符串
$missingName->url = 'http://localhost:3000';
$missingName->token = 'valid_token';
r($gogsTest->checkTokenTest($missingName)) && p('result') && e('fail'); // 步骤2：缺少name字段

$missingUrl = new stdclass();
$missingUrl->name = 'TestGogs';
$missingUrl->token = 'valid_token';
$missingUrl->url = '';  // 空url字符串
r($gogsTest->checkTokenTest($missingUrl)) && p('result') && e('fail'); // 步骤3：缺少url字段

$missingToken = new stdclass();
$missingToken->name = 'TestGogs';
$missingToken->url = 'http://localhost:3000';
$missingToken->token = '';  // 空token字符串
r($gogsTest->checkTokenTest($missingToken)) && p('result') && e('fail'); // 步骤4：缺少token字段

$invalidToken = new stdclass();
$invalidToken->name = 'TestGogs';
$invalidToken->url = 'http://invalid-server:3000';
$invalidToken->token = 'invalid_token';
r($gogsTest->checkTokenTest($invalidToken)) && p('result') && e('fail'); // 步骤5：无效token权限