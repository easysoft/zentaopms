#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getListByModuleId();
cid=1
pid=1

*/

$api = new apiTest();

r($api->getListByModuleIdTest(1, 1, 1)) && p('title') && e('获取token');          //通过正常的libID、moduleID、releaseID查询api信息
r($api->getListByModuleIdTest(1, 1, 0)) && p('title') && e('获取我的个人信息');   //通过正常的libID、moduleID,不传releaseID,查询api信息
r($api->getListByModuleIdTest(1, 0, 0)) && p('title') && e('获取token');          //通过正常的libID,不传moduleID、releaseID查询api信息
r($api->getListByModuleIdTest(0, 0, 0)) && p('title') && e('0');                  //通过不存在的libID,moduleID,releaseID查询api信息
