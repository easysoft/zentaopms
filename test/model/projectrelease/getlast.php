#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->getLast();
cid=1
pid=1

执行projectID正常存在 >> 产品1发布9
执行projectID不存在 >> 0
执行projectID为空 >> 8

*/

$projectID = array(11, 1000, '');

$projectrelease = new projectreleaseTest();

r($projectrelease->getLastTest($projectID[0])) && p('name') && e('产品1发布9');  //执行projectID正常存在
r($projectrelease->getLastTest($projectID[1])) && p()       && e('0');           //执行projectID不存在
r($projectrelease->getLastTest($projectID[2])) && p('id')   && e('8');           //执行projectID为空
