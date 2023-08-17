#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(2);

/**

title=测试 projectModel->accessDenied();
timeout=0
cid=1

- 执行$_SESSION['project'] @2

- 执行$_SESSION['project'] @0

*/

global $tester;
$tester->loadModel('project');

$tester->project->checkAccess(2, array(2 => ''));
r($_SESSION['project']) && p() && e('2');

$tester->project->accessDenied();
r($_SESSION['project']) && p() && e('0');
