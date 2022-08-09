#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
$db->switchDB();
su('admin');

/**

title=测试jenkinsModel->create();
cid=1
pid=1

Jenkins名称为空 >> name
服务器地址为空 >> url
正确Jenkins数据 >> Jenkins,http://10.0.1.161:58080,1196c85ba525a268570df9da627e3a7b2d

*/

$jenkins = new jenkinsTest();

$_POST = array();
$_POST['name']     = '';
$_POST['url']      = 'http://10.0.1.161:58080';
$_POST['account']  = 'admin';
$_POST['token']    = '1196c85ba525a268570df9da627e3a7b2d';
$_POST['password'] = '';

r($jenkins->create()) && p() && e('name');    // Jenkins名称为空

$_POST['name'] = 'Jenkins';
$_POST['url']  = '';
r($jenkins->create()) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.1.161:58080';
r($jenkins->create()) && p('name,url,token') && e('Jenkins,http://10.0.1.161:58080,1196c85ba525a268570df9da627e3a7b2d');    // 正确Jenkins数据

$db->restoreDB();