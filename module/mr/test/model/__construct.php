#!/usr/bin/env php
<?php

/**

title=测试 mrModel::__construct();
timeout=0
cid=17220

- 步骤1：默认参数，rawModule为mr @mr
- 步骤2：指定appName，rawModule为mr @mr
- 步骤3：默认参数，rawModule为pullreq @pullreq
- 步骤4：指定appName，rawModule为pullreq @pullreq
- 步骤5：边界情况，rawModule为其他值 @mr

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mrTest = new mrTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($mrTest->constructTest('', 'mr')) && p() && e('mr');                    // 步骤1：默认参数，rawModule为mr
r($mrTest->constructTest('zentao', 'mr')) && p() && e('mr');             // 步骤2：指定appName，rawModule为mr
r($mrTest->constructTest('', 'pullreq')) && p() && e('pullreq');         // 步骤3：默认参数，rawModule为pullreq
r($mrTest->constructTest('zentao', 'pullreq')) && p() && e('pullreq');   // 步骤4：指定appName，rawModule为pullreq
r($mrTest->constructTest('', 'other')) && p() && e('mr');                // 步骤5：边界情况，rawModule为其他值