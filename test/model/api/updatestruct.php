#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->updateStruct();
cid=1
pid=1

*/

$api = new apiTest();

r($api->updateStructTest()) && p() && e();