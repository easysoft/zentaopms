#!/usr/bin/env php
<?php

/**

title=测试 docModel->checkPrivDoc();
timeout=0
cid=1

- 检查管理员对于没有libID文档权限 @0
- 检查管理员对于资产库下文档权限 @1
- 检查管理员对于私有文档权限 @1
- 检查管理员对于非他创建的草稿文档权限 @0
- 检查管理员对于有权限查看文档库下的文档权限 @1
- 检查管理员对于自定义文档的权限 @1
- 检查普通用户user1对于没有libID文档权限 @0
- 检查普通用户user1对于资产库下文档权限 @1
- 检查普通用户user1对于私有文档权限 @0
- 检查普通用户user1对于非他创建的草稿文档权限 @0
- 检查普通用户user1对于有权限查看文档库下的文档权限 @0
- 检查普通用户user1对于自定义文档的权限 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$docTable = zdTable('doc')->config('doc');
$docTable->assetLibType->range('practice,component,[]{18}');
$docTable->users->range('user1');
$docTable->addedBy->range('admin{5},user1,admin{14}');
$docTable->gen(20);

zdTable('doclib')->config('doclib')->gen(20);
zdTable('user')->gen(5);

$users  = array('admin', 'user1');
$docIds = array(0, 1, 3, 6, 12, 14);

$docTester = new docTest();

/* Check admin privilege. */
r($docTester->checkPrivDocTest($users[0], $docIds[0])) && p() && e('0'); // 检查管理员对于没有libID文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[1])) && p() && e('1'); // 检查管理员对于资产库下文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[2])) && p() && e('1'); // 检查管理员对于私有文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[3])) && p() && e('0'); // 检查管理员对于非他创建的草稿文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[4])) && p() && e('1'); // 检查管理员对于有权限查看文档库下的文档权限
r($docTester->checkPrivDocTest($users[0], $docIds[5])) && p() && e('1'); // 检查管理员对于自定义文档的权限

/* Check user1 privilege. */
r($docTester->checkPrivDocTest($users[1], $docIds[0])) && p() && e('0'); // 检查普通用户user1对于没有libID文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[1])) && p() && e('1'); // 检查普通用户user1对于资产库下文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[2])) && p() && e('0'); // 检查普通用户user1对于私有文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[3])) && p() && e('0'); // 检查普通用户user1对于非他创建的草稿文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[4])) && p() && e('0'); // 检查普通用户user1对于有权限查看文档库下的文档权限
r($docTester->checkPrivDocTest($users[1], $docIds[5])) && p() && e('0'); // 检查普通用户user1对于自定义文档的权限
