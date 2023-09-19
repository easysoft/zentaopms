#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->delete();
timeout=0
cid=1

- 测试删除之后deleted值是否为1属性deleted @1

*/

$caselib = new caselibTest();

r($caselib->deleteTest(1)) && p('deleted') && e('1');  //测试删除之后deleted值是否为1