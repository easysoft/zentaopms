#!/usr/bin/env php
<?php

/* 与 Web 入口一致，确保 $app->wwwRoot、dataRoot、tmpRoot 指向应用 www 目录 */
$_SERVER['SCRIPT_FILENAME'] = dirname(__FILE__, 5) . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'index.php';

/**

title=测试 userZen::checkDirPermission();
timeout=0
cid=19690

- 执行userTest模块的checkDirPermissionTest方法  @1
- 执行userTest模块的checkDirPermissionTest方法  @1
- 执行userTest模块的checkDirPermissionTest方法  @1
- 执行userTest模块的checkDirPermissionTest方法  @1
- 执行userTest模块的checkDirPermissionTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userTest = new userZenTest();

r($userTest->checkDirPermissionTest()) && p() && e(1);
r($userTest->checkDirPermissionTest()) && p() && e(1);
r($userTest->checkDirPermissionTest()) && p() && e(1);
r($userTest->checkDirPermissionTest()) && p() && e(1);
r($userTest->checkDirPermissionTest()) && p() && e(1);