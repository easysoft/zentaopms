#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('doclib')->gen(1);

/**

title=测试 projectModel->createProductDocLib();
timeout=0
cid=17895

- 测试创建产品文档库
 - 属性id @2
 - 属性type @product
 - 属性name @产品主库
 - 属性product @10
 - 属性addedBy @admin

*/

$projectTester = new projectTest();
r($projectTester->createProductDocLibTest(10)) && p('id,type,name,product,addedBy') && e('2,product,产品主库,10,admin');  // 测试创建产品文档库
