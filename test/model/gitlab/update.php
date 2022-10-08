#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->update();
cid=1
pid=1

GitLab名称为空    >> name
服务器地址为空     >> url
正确GitLab数据    >> Changed GitLab

*/

$gitlab = new gitlabTest();

$gitlabID = 3;

$_POST = array();
$_POST['name']     = '';
$_POST['url']      = 'http://10.0.7.242:9980';
$_POST['account']  = 'admin';
$_POST['token']    = 'y2UBqwPPzaLxsniy8R6A';
$_POST['password'] = '';

r($gitlab->update($gitlabID)) && p() && e('name');    // GitLab名称为空

$_POST['name']     = 'Changed GitLab';
$_POST['url']      = '';
r($gitlab->update($gitlabID)) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9980';
r($gitlab->update($gitlabID)) && p('name') && e('Changed GitLab');    // 正确GitLab数据

system("./ztest init");
