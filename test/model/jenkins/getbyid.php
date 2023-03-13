#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->gitById();
cid=1
pid=1

使用存在的ID >> 3

*/

$jenkins = new jenkinsTest();

$jenkinsID = 3;
r($jenkins->getById($jenkinsID)) && p('id') && e('3');    // 使用存在的ID

$jenkinsID = 0;
r($jenkins->getById($jenkinsID)) && p() && e(0);     // 使用空的ID

$jenkinsID = 111;
r($jenkins->getById($jenkinsID)) && p() && e(0);     // 使用不存在的ID

