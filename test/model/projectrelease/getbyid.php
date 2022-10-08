#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->getByID();
cid=1
pid=1

根据releaseID查找发布详情，能查询到结果 >> 产品1正常的发布1
根据releaseID查找发布详情，不能查询到结果 >> 0

*/

$releaseID = array(1, 100);

$projectrelease = new projectreleaseTest();

r($projectrelease->getByIDTest($releaseID[0])) && p('name') && e('产品1正常的发布1'); //根据releaseID查找发布详情，能查询到结果
r($projectrelease->getByIDTest($releaseID[1])) && p('name') && e('0');                //根据releaseID查找发布详情，不能查询到结果
