#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
$doclib = zdTable('doclib');
$doclib->id->range('1');
$doclib->gen(0);

zdTable('user')->gen(5);
su('admin');

/**

title=测试 docModel->createApiLib();
cid=1
pid=1

新建公有接口库 >> api;新建api文档库
新建私有接口库 >> private;www.zentaopms.com
新建自定义接口库 >> custom;1,2,3;admin,dev1,dev10
接口库名称为空 >> 『接口库名称』不能为空。
重复创建接口库 >> 『接口库名称』已经有『新建api文档库』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/
$acl       = array('', 'open', 'custom', 'private');
$groups    = array('1', '2', '3');
$users     = array('admin', 'dev1', 'dev10');

$openApilib    = array('name' => '新建api文档库', 'acl' => $acl[1], 'baseUrl' => 'www.zentaopms.com');
$privateApilib = array('name' => '新建私有api文档库', 'acl' => $acl[3], 'baseUrl' => 'www.zentaopms.com');
$customApilib  = array('name' => '新建自定义api文档库', 'acl' => $acl[2], 'baseUrl' => 'www.zentaopms.com', 'groups' => $groups, 'users' => $users);
$noName        = array('name' => '', 'acl' => $acl[1], 'baseUrl' => 'www.zentaopms.com');
$noAcl         = array('name' => '无权限', 'acl' => $acl[0], 'baseUrl' => 'www.zentaopms.com');
$noBaseUrl     = array('name' => 'noBaseUrl', 'acl' => $acl[0], 'baseUrl' => '');

$doc = new docTest();

r($doc->createApiLibTest($openApilib))    && p('type;name')        && e('api;新建api文档库');            //新建公有接口库
r($doc->createApiLibTest($privateApilib)) && p('acl;baseUrl')      && e('private;www.zentaopms.com');    //新建私有接口库
r($doc->createApiLibTest($customApilib))  && p('acl;groups;users') && e('custom;1,2,3;admin,dev1,dev10');//新建自定义接口库
r($doc->createApiLibTest($noName))        && p('name:0')           && e('『接口库名称』不能为空。');     //接口库名称为空
r($doc->createApiLibTest($noAcl))         && p('acl')              && e('');                             //接口库权限为空
r($doc->createApiLibTest($noBaseUrl))     && p('baseUrl')          && e('');                             //接口库地址为空
r($doc->createApiLibTest($openApilib))    && p('name:0')           && e('『接口库名称』已经有『新建api文档库』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');//重复创建接口库

