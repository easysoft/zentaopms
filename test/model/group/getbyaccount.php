#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getByAccount();
cid=1
pid=1

测试获取test2 的信息 >> 3,测试,qa
测试获取dev2 的信息 >> 2,研发,dev
测试获取po48 的信息 >> 2,研发,dev
测试获取td2 的信息 >> 2,研发,dev
测试获取pd2 的信息 >> 3,测试,qa

*/

$group = new groupTest();
r($group->getByAccountTest('test2')) && p('3:id,name,role') && e('3,测试,qa');  //测试获取test2 的信息
r($group->getByAccountTest('dev2'))  && p('2:id,name,role') && e('2,研发,dev'); //测试获取dev2 的信息
r($group->getByAccountTest('po48'))  && p('2:id,name,role') && e('2,研发,dev'); //测试获取po48 的信息
r($group->getByAccountTest('td2'))   && p('2:id,name,role') && e('2,研发,dev'); //测试获取td2 的信息
r($group->getByAccountTest('pd2'))   && p('3:id,name,role') && e('3,测试,qa');  //测试获取pd2 的信息