#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=测试 executionModel::accessDenied();
cid=1
pid=1

权限不足跳转 >> html

*/

global $tester;
$tester->loadModel('execution');
ob_start();
$tester->execution->accessDenied();
$result = ob_get_clean();

r(substr($result, 1, 4)) && p() && e('html'); // 权限不足跳转
