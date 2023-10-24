#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitea.class.php';
su('admin');

/**

title=测试giteaModel->gitPairs();
cid=1
pid=1

获取Gitea   >> 4

*/

$gitea = new giteaTest();

r($gitea->getPairs()) && p() && e('4');    // 获取Gitea

