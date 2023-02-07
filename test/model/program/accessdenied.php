#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::accessDenied();
cid=1
pid=1

权限不足跳转 >> html

*/

global $tester;
$tester->loadModel('program');
ob_start();
$tester->program->accessDenied();
$result = ob_get_clean();

r(substr($result, 1, 4)) && p() && e('html'); // 权限不足跳转
