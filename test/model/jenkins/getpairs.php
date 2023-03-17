#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->gitPairs();
cid=1
pid=1

获取Jenkins >> 3

*/

$jenkins = new jenkinsTest();

r($jenkins->getPairs()) && p() && e('3');    // 获取Jenkins

