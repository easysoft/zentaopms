#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/ci.class.php';
su('admin');

/**

title=测试 ciModel->syncCompileStatus();
cid=1
pid=1

同步jenkins构建结果 >> created

*/

$ci = new ciTest();

r($ci->syncCompileStatusTest(1)) && p('status') && e('created'); //同步jenkins构建结果