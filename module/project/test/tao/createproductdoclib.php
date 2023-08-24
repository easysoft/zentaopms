#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('doclib')->gen(1);

/**

title=测试 projectModel->createProductDocLib();
timeout=0
cid=1

*/

$projectTester = new project();
r($projectTester->createProductDocLibTest(10)) && p('name,product') && e('产品主库,10');  // 测试创建产品文档库
