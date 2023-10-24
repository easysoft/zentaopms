#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gogs.class.php';
su('admin');

/**

title=测试gogsModel->gitPairs();
cid=1
pid=1

获取Gogs   >> 5

*/

$gogs = new gogsTest();

r($gogs->getPairs()) && p() && e('5');    // 获取Gogs

