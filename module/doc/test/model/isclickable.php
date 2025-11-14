#!/usr/bin/env php
<?php

/**

title=测试 docModel::isClickable();
timeout=0
cid=16142

- 步骤1：当前用户文档，movedoc操作 @1
- 步骤2：其他用户文档，movedoc操作 @0
- 步骤3：当前用户文档，edit操作 @1
- 步骤4：其他用户文档，edit操作 @1
- 步骤5：空文档对象，movedoc操作 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->gen(10);

su('admin');

$docTest = new docTest();

// 创建测试用的文档对象
$currentUserDoc = new stdClass();
$currentUserDoc->addedBy = 'admin';

$otherUserDoc = new stdClass();
$otherUserDoc->addedBy = 'user';

$emptyDoc = new stdClass();
$emptyDoc->addedBy = '';

r($docTest->isClickableTest($currentUserDoc, 'movedoc')) && p() && e('1');       // 步骤1：当前用户文档，movedoc操作
r($docTest->isClickableTest($otherUserDoc, 'movedoc')) && p() && e('0');        // 步骤2：其他用户文档，movedoc操作
r($docTest->isClickableTest($currentUserDoc, 'edit')) && p() && e('1');         // 步骤3：当前用户文档，edit操作
r($docTest->isClickableTest($otherUserDoc, 'edit')) && p() && e('1');           // 步骤4：其他用户文档，edit操作
r($docTest->isClickableTest($emptyDoc, 'movedoc')) && p() && e('0');            // 步骤5：空文档对象，movedoc操作