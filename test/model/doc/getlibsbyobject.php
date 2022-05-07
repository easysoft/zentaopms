#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLibsByObject();
cid=1
pid=1



*/

$doc = new docTest();

r($doc->getLibsByObjectTest()) && p() && e();