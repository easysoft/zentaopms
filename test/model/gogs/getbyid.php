#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gogs.class.php';
su('admin');

/**

title=测试gogsModel->gitById();
cid=1
pid=1

使用存在的ID    >> 5
使用空的ID      >> 0
使用不存在的ID  >> 0

*/

$gogs = new gogsTest();

$gogsID = 5;
r($gogs->getById($gogsID)) && p('id') && e('5');    // 使用存在的ID

$gogsID = 0;
r($gogs->getById($gogsID)) && p() && e(0);     // 使用空的ID

$gogsID = 111;
r($gogs->getById($gogsID)) && p() && e(0);     // 使用不存在的ID

