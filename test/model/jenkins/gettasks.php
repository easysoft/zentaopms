#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->gitTasks();
cid=1
pid=1

使用存在的Jenkins >> zentao-test
使用空的Jenkins >> 0
使用不存在的Jenkins >> 0

*/

$jenkins = new jenkinsTest();

$id = 3;
r($jenkins->getTasks($id)) && p('zentao-test') && e('zentao-test');    // 使用存在的Jenkins

$id = 0;
r($jenkins->getTasks($id)) && p() && e('0');    // 使用空的Jenkins

$id = 111;
r($jenkins->getTasks($id)) && p() && e('0');    // 使用不存在的Jenkins

