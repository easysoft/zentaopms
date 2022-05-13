#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/search.class.php';
su('admin');

/**

title=测试 searchModel->checkPriv();
cid=1
pid=1

*/

$search = new searchTest();

r($search->checkPrivTest()) && p() && e();