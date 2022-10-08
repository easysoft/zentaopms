#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getProductUnitTasks();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('testtask');

$tasks = $tester->testtask->getProductUnitTasks(1, 'all');

r($testtask->getProductUnitTasksTest()) && p() && e();