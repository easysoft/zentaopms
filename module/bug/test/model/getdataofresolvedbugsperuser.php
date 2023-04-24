#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php"; su('admin');
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->getDataOfResolvedBugsPerUser();
cid=1
pid=1

获取admin解决的bug数 >> admin,60
获取 >> ,90

*/

$bug=new bugTest();
r($bug->getDataOfResolvedBugsPerUserTest()) && p('admin:name,value')       && e('admin,60');       // 获取admin解决的bug数
r($bug->getDataOfResolvedBugsPerUserTest()) && p('$assignedTo:name,value') && e('$assignedTo,90'); // 获取$assignTo解决的bug数