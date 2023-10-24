#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitea.class.php';
su('admin');

/**

title=测试giteaModel->create();
cid=1
pid=1

Gitea名称为空    >> name
服务器地址为空   >> url
正确Gitea数据    >> Gitea,http://10.0.7.242:9020,1196c85ba525a268570df9da627e3a7b2d

*/

$gitea = new giteaTest();

$_POST = array();
$_POST['name']  = '';
$_POST['url']   = 'http://10.0.7.242:9020';
$_POST['token'] = 'c6769e6761a7d719129b2421dcb3112d936e2b1f';

r($gitea->create()) && p() && e('name');    // Gitea名称为空

$_POST['name'] = 'Gitea';
$_POST['url']  = '';
r($gitea->create()) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9020';
r($gitea->create()) && p('name,url,token') && e('Gitea,http://10.0.7.242:9020,c6769e6761a7d719129b2421dcb3112d936e2b1f');    // 正确Gitea数据

