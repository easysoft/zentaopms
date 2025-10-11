#!/usr/bin/env php
<?php

/**

title=测试 chartModel::checkAccess();
timeout=0
cid=0

- 执行chartTest模块的checkAccessTest方法，参数是1, 'preview'  @~~
- 执行chartTest模块的checkAccessTest方法，参数是2, 'edit'  @~~
- 执行chartTest模块的checkAccessTest方法，参数是1, 'preview'  @~~
- 执行chartTest模块的checkAccessTest方法，参数是3, 'view'  @~~
- 执行chartTest模块的checkAccessTest方法，参数是2, 'preview'  @access_denied

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 登录管理员
su('admin');

// 创建测试实例
$chartTest = new chartTest();

// 测试步骤1：管理员访问有权限的图表
r($chartTest->checkAccessTest(1, 'preview')) && p() && e('~~');

// 测试步骤2：管理员访问其他图表
r($chartTest->checkAccessTest(2, 'edit')) && p() && e('~~');

// 切换到普通用户
su('user');

// 测试步骤3：普通用户访问有权限的图表
r($chartTest->checkAccessTest(1, 'preview')) && p() && e('~~');

// 测试步骤4：普通用户访问有权限的其他图表
r($chartTest->checkAccessTest(3, 'view')) && p() && e('~~');

// 测试步骤5：普通用户访问无权限的图表
r($chartTest->checkAccessTest(2, 'preview')) && p() && e('access_denied');