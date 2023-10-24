#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitea.class.php';
su('admin');

/**

title=测试giteaModel->gitList();
cid=1
pid=1

获取Gitea列表 >> 4

*/

$gitea = new giteaTest();

$orderBy = 'id_desc';
r($gitea->getList($orderBy)) && p('id') && e('4');    // 获取Gitea列表

