#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getUserBugPairs();
cid=1
pid=1

测试获取用户admin的bug >> 85
测试获取用户test1的bug >> 65
测试获取用户test2的bug >> 0
测试获取用户dev1的bug >> 55
测试获取用户po1的bug >> 0

*/

$accountIDList = array('admin', 'test1', 'test2', 'dev1', 'po1');

$bug=new bugTest();
r($bug->getUserBugPairsTest($accountIDList[0])) && p() && e('85'); // 测试获取用户admin的bug
r($bug->getUserBugPairsTest($accountIDList[1])) && p() && e('65'); // 测试获取用户test1的bug
r($bug->getUserBugPairsTest($accountIDList[2])) && p() && e('0');  // 测试获取用户test2的bug
r($bug->getUserBugPairsTest($accountIDList[3])) && p() && e('55'); // 测试获取用户dev1的bug
r($bug->getUserBugPairsTest($accountIDList[4])) && p() && e('0');  // 测试获取用户po1的bug