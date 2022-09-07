#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->create();
cid=1
pid=1

GitLab名称为空    >> name
服务器地址为空     >> url
正确GitLab数据    >> GitLab,http://10.0.7.242:9980,1196c85ba525a268570df9da627e3a7b2d

*/

$gitlab = new gitlabTest();

$_POST = array();
$_POST['name']     = '';
$_POST['url']      = 'http://10.0.7.242:9980';
$_POST['token']    = 'y2UBqwPPzaLxsniy8R6A';
$_POST['password'] = '';

r($gitlab->create()) && p() && e('name');    // GitLab名称为空

$_POST['name'] = 'GitLab';
$_POST['url']  = '';
r($gitlab->create()) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9980';
r($gitlab->create()) && p('name,url,token') && e('GitLab,http://10.0.7.242:9980,y2UBqwPPzaLxsniy8R6A');    // 正确GitLab数据

system("./ztest init");
