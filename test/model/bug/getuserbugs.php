#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getUserBugs();
cid=1
pid=1

测试获取用户admin的bug >> 85
测试获取用户test1的bug >> 85
测试获取用户test2的bug >> 0
测试获取用户dev1的bug >> 55
测试获取用户po1的bug >> 0

*/

$accountIDList = array('admin', 'test1', 'test2', 'dev1', 'po1');

$bug=new bugTest();
r($bug->getUserBugsTest($accountIDList[0])) && p() && e('85'); // 测试获取用户admin的bug
r($bug->getUserBugsTest($accountIDList[1])) && p() && e('85'); // 测试获取用户test1的bug
r($bug->getUserBugsTest($accountIDList[2])) && p() && e('0');  // 测试获取用户test2的bug
r($bug->getUserBugsTest($accountIDList[3])) && p() && e('55'); // 测试获取用户dev1的bug
r($bug->getUserBugsTest($accountIDList[4])) && p() && e('0');  // 测试获取用户po1的bug