#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateApiLib();
cid=1

- 正常修改接口库
 - 第0条的field属性 @name
 - 第0条的old属性 @项目接口库1
 - 第0条的new属性 @修改api文档库
 - 第1条的field属性 @baseUrl
 - 第1条的old属性 @~~
 - 第1条的new属性 @www.zentaopms.com
 - 第2条的field属性 @acl
 - 第2条的old属性 @default
 - 第2条的new属性 @custom
- 修改users
 - 第1条的field属性 @users
 - 第1条的old属性 @admin
 - 第1条的new属性 @test1
- 修改名字为空第name条的0属性 @『库名称』不能为空。
- 修改成重复名称
 - 第0条的field属性 @name
 - 第0条的old属性 @项目接口库1
 - 第0条的new属性 @修改api文档库

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(2);
zdTable('user')->gen(5);
su('admin');

$ids    = array(1, 2);
$groups = '1,2,3';
$users  = 'admin,dev1,dev10';

$openApilib  = array('type' => 'project', 'product' => 0, 'project' => 11, 'name' => '修改api文档库', 'baseUrl' => 'www.zentaopms.com', 'acl' => 'custom', 'users' => 'admin', 'desc' => '');
$updateUsers = array('type' => 'project', 'product' => 0, 'project' => 11, 'name' => '项目接口库1',   'users' => 'test1');
$updateDesc  = array('type' => 'project', 'product' => 0, 'project' => 11, 'name' => '项目接口库1',   'desc' => '修改详情');
$noName      = array('type' => 'project', 'product' => 0, 'project' => 11, 'name' => '');
$repeatName  = array('type' => 'project', 'product' => 0, 'project' => 11, 'name' => '修改api文档库');

$docTester = new docTest();
r($docTester->updateApiLibTest($ids[0], $openApilib))  && p('0:field,old,new;1:field,old,new;2:field,old,new') && e('name,项目接口库1,修改api文档库;baseUrl,~~,www.zentaopms.com;acl,default,custom'); // 正常修改接口库
r($docTester->updateApiLibTest($ids[0], $updateUsers)) && p('1:field,old,new')                                 && e('users,admin,test1');                                                              // 修改users
r($docTester->updateApiLibTest($ids[0], $noName))      && p('name:0')                                          && e('『库名称』不能为空。');                                                           // 修改名字为空
r($docTester->updateApiLibTest($ids[0], $repeatName))  && p('0:field,old,new')                                 && e('name,项目接口库1,修改api文档库');                                                 // 修改成重复名称
