#!/usr/bin/env php
<?php

/**

title=测试 commonModel::initAuthorize();
timeout=0
cid=15682

- 步骤1：无用户登录情况属性result @0
- 步骤2：升级过程中的用户属性result @1
- 步骤3：正常用户登录非升级状态属性result @1
- 步骤4：普通用户权限初始化属性result @1
- 步骤5：guest用户权限初始化属性result @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 创建测试实例
$commonTest = new commonTest();

// 3. 测试步骤
r($commonTest->initAuthorizeTest('', false)) && p('result') && e('0'); // 步骤1：无用户登录情况
r($commonTest->initAuthorizeTest('admin', true)) && p('result') && e('1'); // 步骤2：升级过程中的用户
r($commonTest->initAuthorizeTest('admin', false)) && p('result') && e('1'); // 步骤3：正常用户登录非升级状态
r($commonTest->initAuthorizeTest('user1', false)) && p('result') && e('1'); // 步骤4：普通用户权限初始化
r($commonTest->initAuthorizeTest('guest', false)) && p('result') && e('1'); // 步骤5：guest用户权限初始化