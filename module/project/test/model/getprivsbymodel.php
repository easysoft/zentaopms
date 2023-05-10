#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

/**

title=测试 projectModel->getPrivsByModel();
timeout=0
cid=1

*/

global $tester;
$projectTester = $tester->loadModel('project');

r($projectTester->getPrivsByModel(''))         && p()             && e('0');            // 传递空类型的情况
r($projectTester->getPrivsByModel('test'))     && p()             && e('0');            // 传递错误的类型的情况
r($projectTester->getPrivsByModel('scrum'))    && p('bug:delete') && e('deleteAction'); // 传递正确类型的情况
