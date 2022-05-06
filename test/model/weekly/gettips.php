#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getTips();
cid=1
pid=1

*/

$weekly = new weeklyTest();

r($weekly->getTipsTest()) && p() && e();