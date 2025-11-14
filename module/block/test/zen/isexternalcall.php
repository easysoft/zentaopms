#!/usr/bin/env php
<?php

/**

title=测试 blockZen::isExternalCall();
timeout=0
cid=15245

- 步骤1：无hash参数时，期望返回false @0
- 步骤2：有hash参数时，期望返回true @1
- 步骤3：hash参数为空字符串，期望返回true @1
- 步骤4：hash参数为0，期望返回true @1
- 步骤5：同时存在多个GET参数，期望返回true @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例
$blockTest = new blockTest();

// 4. 测试步骤 - 必须包含至少5个测试步骤

// 清空$_GET参数，确保测试环境干净
$_GET = array();

r($blockTest->isExternalCallTest()) && p() && e('0'); // 步骤1：无hash参数时，期望返回false

$_GET['hash'] = 'test123';
r($blockTest->isExternalCallTest()) && p() && e('1'); // 步骤2：有hash参数时，期望返回true

$_GET['hash'] = '';
r($blockTest->isExternalCallTest()) && p() && e('1'); // 步骤3：hash参数为空字符串，期望返回true

$_GET['hash'] = '0';
r($blockTest->isExternalCallTest()) && p() && e('1'); // 步骤4：hash参数为0，期望返回true

$_GET['hash'] = 'abc123';
$_GET['other'] = 'value';
r($blockTest->isExternalCallTest()) && p() && e('1'); // 步骤5：同时存在多个GET参数，期望返回true