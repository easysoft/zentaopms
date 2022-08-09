#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->parseXMLResult();
cid=1
pid=1



*/

$testtask = new testtaskTest();

r($testtask->parseXMLResultTest()) && p() && e();