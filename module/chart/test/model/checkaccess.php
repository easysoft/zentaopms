#!/usr/bin/env php
<?php

/**

title=测试 chartModel::checkAccess();
timeout=0
cid=15564

- 步骤1:管理员admin访问有权限的图表ID=1 @success
- 步骤2:管理员admin访问有权限的图表ID=5,method='view' @success
- 步骤3:普通用户user1访问有权限的图表ID=3 @success
- 步骤4:普通用户user1访问无权限的图表ID=6 @access_denied
- 步骤5:访客用户test1访问无权限的图表ID=10 @access_denied

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

zenData('user')->gen(10);
su('admin');

$chartTest = new chartTest();

r($chartTest->checkAccessTest(1, 'preview')) && p() && e('success'); // 步骤1:管理员admin访问有权限的图表ID=1
r($chartTest->checkAccessTest(5, 'view')) && p() && e('success'); // 步骤2:管理员admin访问有权限的图表ID=5,method='view'
su('user1');
r($chartTest->checkAccessTest(3, 'preview')) && p() && e('success'); // 步骤3:普通用户user1访问有权限的图表ID=3
r($chartTest->checkAccessTest(6, 'preview')) && p() && e('access_denied'); // 步骤4:普通用户user1访问无权限的图表ID=6
su('test1');
r($chartTest->checkAccessTest(10, 'preview')) && p() && e('access_denied'); // 步骤5:访客用户test1访问无权限的图表ID=10