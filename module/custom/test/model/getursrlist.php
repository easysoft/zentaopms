#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';
su('admin');

/**

title=测试 customModel->getURSRList();
cid=1
pid=1

测试正常查询 >> 软件需求,用户需求,1

*/

$custom = new customTest();

r($custom->getURSRListTest()) && p('1:SRName,URName,system') && e('软件需求,用户需求,1');  //测试正常查询