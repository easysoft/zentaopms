#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getToAndCcList();
cid=1
pid=1

正常发布发信列表 >> admin;
停止维护发布发信列表 >> admin;

*/
$releaseID = array('1','6');

$release = new releaseTest();
r($release->getToAndCcListTest($releaseID[0])) && p('0;1') && e('admin;'); //正常发布发信列表
r($release->getToAndCcListTest($releaseID[1])) && p('0;1') && e('admin;'); //停止维护发布发信列表