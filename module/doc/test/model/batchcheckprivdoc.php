#!/usr/bin/env php
<?php

/**

title=测试 docModel->batchCheckPrivDoc();
cid=16042

- 传入空数组 @0
- 检查管理员有权限的文档数 @5
- 检查管理员对于id=1文档权限 @1
- 检查管理员对于id=6文档权限 @0
- 检查 user1 有权限的文档数 @6
- 检查 user1 对于id=1文档权限 @1
- 检查 user1 对于id=6文档权限 @1

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
$docIds = array(1, 3, 6, 12, 14, 20);

$docTester = new docModelTest();

r(count($docTester->batchCheckPrivDocTest($users[0], array()))) && p() && e('0'); // 传入空数组

/* Check admin privilege. */
$docs = $docTester->batchCheckPrivDocTest($users[0], $docIds);
r(count($docs)) && p() && e('5');         // 检查管理员有权限的文档数
r((int)isset($docs[1])) && p() && e('1'); // 检查管理员对于id=1文档权限
r((int)isset($docs[6])) && p() && e('0'); // 检查管理员对于id=6文档权限

/* Check user1 privilege. */
$docs = $docTester->batchCheckPrivDocTest($users[1], $docIds);
r(count($docs)) && p() && e('6');         // 检查 user1 有权限的文档数
r((int)isset($docs[1])) && p() && e('1'); // 检查 user1 对于id=1文档权限
r((int)isset($docs[6])) && p() && e('1'); // 检查 user1 对于id=6文档权限
