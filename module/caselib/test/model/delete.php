#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->delete();
cid=1
pid=1

测试删除之后deleted值是否为1 >> 1

*/

$caselib = new caselibTest();
$lib     = $caselib->deleteTest(201);

r($lib) && p('deleted') && e('1');  //测试删除之后deleted值是否为1

