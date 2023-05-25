#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->gitPairs();
cid=1
pid=1

获取Jenkins >> 3

*/

$jenkins = new jenkinsTest();

r($jenkins->getPairs()) && p() && e('3');    // 获取Jenkins

