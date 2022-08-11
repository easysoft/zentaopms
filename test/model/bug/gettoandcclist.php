#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getToAndCcList();
cid=1
pid=1

测试获取bug1的联系人 >> admin,admin
测试获取bug2的联系人 >> admin,admin
测试获取bug3的联系人 >> admin,admin
测试获取bug51的联系人 >> dev1,admin
测试获取bug81的联系人 >> test1,admin

*/

$bugIDList = array('1', '2', '3', '51', '81');

$bug=new bugTest();
r($bug->getToAndCcListTest($bugIDList[0])) && p() && e('admin,admin'); // 测试获取bug1的联系人
r($bug->getToAndCcListTest($bugIDList[1])) && p() && e('admin,admin'); // 测试获取bug2的联系人
r($bug->getToAndCcListTest($bugIDList[2])) && p() && e('admin,admin'); // 测试获取bug3的联系人
r($bug->getToAndCcListTest($bugIDList[3])) && p() && e('dev1,admin');  // 测试获取bug51的联系人
r($bug->getToAndCcListTest($bugIDList[4])) && p() && e('test1,admin'); // 测试获取bug81的联系人