#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getTutorialed();
cid=1
pid=1

测试是否能拿到数据 >> 0

*/

$tutorial = new tutorialTest();

r($tutorial->getTutorialedTest()) && p() && e('0'); //测试是否能拿到数据