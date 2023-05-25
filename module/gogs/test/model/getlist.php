#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gogs.class.php';
su('admin');

/**

title=测试gogsModel->gitList();
cid=1
pid=1

获取Gogs列表 >> 5

*/

$gogs = new gogsTest();

$orderBy = 'id_desc';
r($gogs->getList($orderBy)) && p('id') && e('5');    // 获取Gogs列表

