#!/usr/bin/env php
<?php

/**

title=测试 docModel->createApiLib();
cid=1

- 新建公有接口库
 - 属性type @api
 - 属性name @新建api文档库
- 新建私有接口库
 - 属性acl @private
 - 属性baseUrl @www.zentaopms.com
- 新建自定义接口库属性acl @custom
- 接口库名称为空第name条的0属性 @『库名称』不能为空。
- 接口库地址为空属性baseUrl @~~
- 重复创建接口库属性name @同一产品下的接口库中『库名称』已经有『新建api文档库』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->gen(0);
zdTable('user')->gen(5);
su('admin');

$acl       = array('open', 'custom', 'private');
$groups    = '1,2,3';
$users     = 'admin,dev1,dev10';

$openApilib    = array('name' => '新建api文档库',       'acl' => $acl[0], 'baseUrl' => 'www.zentaopms.com');
$privateApilib = array('name' => '新建私有api文档库',   'acl' => $acl[2], 'baseUrl' => 'www.zentaopms.com');
$customApilib  = array('name' => '新建自定义api文档库', 'acl' => $acl[1], 'baseUrl' => 'www.zentaopms.com', 'groups' => $groups, 'users' => $users);
$noName        = array('name' => '',                    'acl' => $acl[0], 'baseUrl' => 'www.zentaopms.com');
$noBaseUrl     = array('name' => 'noBaseUrl',           'acl' => $acl[0], 'baseUrl' => '');

$docTester = new docTest();
r($docTester->createApiLibTest($openApilib))    && p('type;name')   && e('api;新建api文档库');                                                                                                       // 新建公有接口库
r($docTester->createApiLibTest($privateApilib)) && p('acl;baseUrl') && e('private;www.zentaopms.com');                                                                                               // 新建私有接口库
r($docTester->createApiLibTest($customApilib))  && p('acl')         && e('custom');                                                                                                                  // 新建自定义接口库
r($docTester->createApiLibTest($noName))        && p('name:0')      && e('『库名称』不能为空。');                                                                                                    // 接口库名称为空
r($docTester->createApiLibTest($noBaseUrl))     && p('baseUrl')     && e('~~');                                                                                                                      // 接口库地址为空
r($docTester->createApiLibTest($openApilib))    && p('name')        && e('同一产品下的接口库中『库名称』已经有『新建api文档库』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 重复创建接口库
