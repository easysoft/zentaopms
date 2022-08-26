#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->setLibUsers();
cid=1
pid=1

测试获取 产品 1 的团队成员 >> po82,user92,po92,test2,test82,test92,pm72,pm82
测试获取 产品 2 的团队成员 >> po83,user93,po93,test3,test83,test93,pm73,pm83
测试获取 产品 3 的团队成员 >> po84,user94,po94,test4,test84,test94,pm74,pm84
测试获取 执行 101 的团队成员 >> po82,user92
测试获取 执行 102 的团队成员 >> po83,user93
测试获取 执行 103 的团队成员 >> po84,user94
测试获取 项目 11 的团队成员 >> 0

*/
$type     = array('product', 'execution', 'project');
$objectID = array(1, 2, 3, 101, 102, 103, 11);

$doc = new docTest();

r($doc->setLibUsersTest($type[0], $objectID[0])) && p() && e('po82,user92,po92,test2,test82,test92,pm72,pm82'); // 测试获取 产品 1 的团队成员
r($doc->setLibUsersTest($type[0], $objectID[1])) && p() && e('po83,user93,po93,test3,test83,test93,pm73,pm83'); // 测试获取 产品 2 的团队成员
r($doc->setLibUsersTest($type[0], $objectID[2])) && p() && e('po84,user94,po94,test4,test84,test94,pm74,pm84'); // 测试获取 产品 3 的团队成员
r($doc->setLibUsersTest($type[1], $objectID[3])) && p() && e('po82,user92');                                    // 测试获取 执行 101 的团队成员
r($doc->setLibUsersTest($type[1], $objectID[4])) && p() && e('po83,user93');                                    // 测试获取 执行 102 的团队成员
r($doc->setLibUsersTest($type[1], $objectID[5])) && p() && e('po84,user94');                                    // 测试获取 执行 103 的团队成员
r($doc->setLibUsersTest($type[2], $objectID[6])) && p() && e('0');                                              // 测试获取 项目 11 的团队成员