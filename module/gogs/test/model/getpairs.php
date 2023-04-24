#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/gogs.class.php';
su('admin');

/**

title=测试gogsModel->gitPairs();
cid=1
pid=1

获取Gogs   >> 5

*/

$gogs = new gogsTest();

r($gogs->getPairs()) && p() && e('5');    // 获取Gogs

