#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->update();
cid=1
pid=1

Jenkins名称为空 >> name
服务器地址为空 >> url
正确Jenkins数据 >> Changed Jenkins

*/

$jenkins = new jenkinsTest();

$jenkinsID = 3;

$_POST = array();
$_POST['name']     = '';
$_POST['url']      = 'http://10.0.7.242:9580';
$_POST['account']  = 'admin';
$_POST['token']    = '1196c85ba525a268570df9da627e3a7b2d';
$_POST['password'] = '';

r($jenkins->update($jenkinsID)) && p() && e('name');    // Jenkins名称为空

$_POST['name']     = 'Changed Jenkins';
$_POST['url']      = '';
r($jenkins->update($jenkinsID)) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9580';
r($jenkins->update($jenkinsID)) && p('name') && e('Changed Jenkins');    // 正确Jenkins数据

