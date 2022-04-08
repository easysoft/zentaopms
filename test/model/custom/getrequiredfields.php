#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->getRequiredFields();
cid=1
pid=1



*/

$custom = new customTest();

r($custom->getRequiredFieldsTest()) && p() && e();