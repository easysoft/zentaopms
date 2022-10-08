#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfClosedBugsPerUser();
cid=1
pid=1

获取admin关闭的数据 >> admin,100

*/

$bug=new bugTest();
r($bug->getDataOfClosedBugsPerUserTest()) && p('admin:name,value') && e('admin,100');   // 获取admin关闭的数据