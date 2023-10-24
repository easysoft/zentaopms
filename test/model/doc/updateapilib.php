#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->updateApiLib();
cid=1
pid=1

正常修改接口库 >> name,接口库,修改api文档库;baseUrl,,www.zentaopms.com;acl,private,open
修改users >> users,admin,test1
修改名字为空 >> 『接口库名称』不能为空。
修改成重复名称 >> 0

*/
$ids    = array('901', '902');
$acl    = array('', 'open', 'custom', 'private');
$groups = array('1', '2', '3');
$users  = array('admin', 'dev1', 'dev10');

$openApilib  = array('name' => '修改api文档库',  'baseUrl' => 'www.zentaopms.com', 'acl' => $acl[1], 'users' => 'admin', 'desc' => '');
$updateUsers = array('users' => 'test1');
$updateDesc  = array('desc' => '修改详情');
$noName      = array('name' => '');
$repeatName  = array('name' => '修改api文档库');

$doc = new docTest();

r($doc->updateApiLibTest($ids[0], $openApilib))  && p('0:field,old,new;1:field,old,new;2:field,old,new') && e('name,接口库,修改api文档库;baseUrl,,www.zentaopms.com;acl,private,open');//正常修改接口库
r($doc->updateApiLibTest($ids[0], $updateUsers)) && p('0:field,old,new')                                 && e('users,admin,test1');                                                    //修改users
r($doc->updateApiLibTest($ids[0], $noName))      && p('name:0')                                          && e('『接口库名称』不能为空。');                                             //修改名字为空
r($doc->updateApiLibTest($ids[0], $repeatName))  && p()                                                  && e('0');                                                                    //修改成重复名称

