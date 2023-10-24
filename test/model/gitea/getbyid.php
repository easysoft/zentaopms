#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitea.class.php';
su('admin');

/**

title=测试giteaModel->gitById();
cid=1
pid=1

使用存在的ID    >> 4
使用空的ID      >> 0
使用不存在的ID  >> 0

*/

$gitea = new giteaTest();

$giteaID = 4;
r($gitea->getById($giteaID)) && p('id') && e('4');    // 使用存在的ID

$giteaID = 0;
r($gitea->getById($giteaID)) && p() && e(0);     // 使用空的ID

$giteaID = 111;
r($gitea->getById($giteaID)) && p() && e(0);     // 使用不存在的ID

