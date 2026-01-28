#!/usr/bin/env php
<?php

/**

title=测试 docModel->checkPrivDoc();
timeout=0
cid=16054

- 检查管理员对于没有libID文档权限 @0
- 检查管理员对于资产库下文档权限 @1
- 检查管理员对于私有文档权限 @1
- 检查管理员对于非他创建的草稿文档权限 @0
- 检查管理员对于有权限查看文档库下的文档权限 @1
- 检查管理员对于自定义文档的权限 @1
- 检查管理员对于文档模板权限 @1
- 检查普通用户user1对于没有libID文档权限 @0
- 检查普通用户user1对于资产库下文档权限 @1
- 检查普通用户user1对于私有文档权限 @0
- 检查普通用户user1对于非他创建的草稿文档权限 @0
- 检查普通用户user1对于有权限查看文档库下的文档权限 @0
- 检查普通用户user1对于自定义文档的权限 @0
- 检查普通用户对于文档模板权限 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$docTable = zenData('doc')->loadYaml('doc');
$docTable->assetLibType->range('practice,component,[]{18}');
$docTable->users->range('user1');
$docTable->addedBy->range('admin{5},user1,admin{14}');
$docTable->type->range('text{19},article');
$docTable->gen(20);

zenData('doclib')->loadYaml('doclib')->gen(20);
zenData('user')->gen(5);

$users  = array('admin', 'user1');
$docIds = array(0, 1, 3, 6, 12, 14, 20);

$docTester = new docModelTest();

/* Check admin privilege. */
r($docTester->checkPrivDocTest($users[0], $docIds[0])) && p() && e('0'); // 检查管理员对于没有libID文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[1])) && p() && e('1'); // 检查管理员对于资产库下文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[2])) && p() && e('1'); // 检查管理员对于私有文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[3])) && p() && e('0'); // 检查管理员对于非他创建的草稿文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[4])) && p() && e('1'); // 检查管理员对于有权限查看文档库下的文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[5])) && p() && e('1'); // 检查管理员对于自定义文档的权限
r($docTester->checkPrivDocTest($users[0], $docIds[6])) && p() && e('1'); // 检查管理员对于文档模板权限

/* Check user1 privilege. */
r($docTester->checkPrivDocTest($users[1], $docIds[0])) && p() && e('0'); // 检查普通用户user1对于没有libID文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[1])) && p() && e('1'); // 检查普通用户user1对于资产库下文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[2])) && p() && e('0'); // 检查普通用户user1对于私有文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[3])) && p() && e('0'); // 检查普通用户user1对于非他创建的草稿文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[4])) && p() && e('0'); // 检查普通用户user1对于有权限查看文档库下的文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[5])) && p() && e('0'); // 检查普通用户user1对于自定义文档的权限
r($docTester->checkPrivDocTest($users[1], $docIds[6])) && p() && e('1'); // 检查普通用户对于文档模板权限