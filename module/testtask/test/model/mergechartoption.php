#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->mergeChartOption();
cid=1
pid=1



*/

$testtask = new testtaskTest();

r($testtask->mergeChartOptionTest()) && p() && e();