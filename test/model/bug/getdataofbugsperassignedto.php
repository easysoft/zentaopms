#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerAssignedTo();
cid=1
pid=1

获取用户admin数据 >> admin,85
获取用户dev1数据 >> 开发1,85
获取用户test1数据 >> 测试1,85
获取用户closed数据 >> Closed,20

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerAssignedToTest()) && p('admin:name,value')  && e('admin,85');  // 获取用户admin数据
r($bug->getDataOfBugsPerAssignedToTest()) && p('dev1:name,value')   && e('开发1,85');   // 获取用户dev1数据
r($bug->getDataOfBugsPerAssignedToTest()) && p('test1:name,value')  && e('测试1,85');  // 获取用户test1数据
r($bug->getDataOfBugsPerAssignedToTest()) && p('closed:name,value') && e('Closed,20'); // 获取用户closed数据