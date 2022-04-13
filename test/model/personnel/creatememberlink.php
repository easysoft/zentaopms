#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->createMemberLink();
cid=1
pid=1

*/

$personnel = new personnelTest();

r($personnel->createMemberLinkTest()) && p() && e();