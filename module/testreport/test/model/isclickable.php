#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

/**

title=测试 testreportModel->isClickable();
cid=19125

- 测试 edit 方法是否可以点击 @1
- 测试 delete 方法是否可以点击 @1
- 测试 create 方法是否可以点击 @1
- 测试 view 方法是否可以点击 @1
- 测试 browse 方法是否可以点击 @1

*/

$object     = new stdclass();
$actionList = array('edit', 'delete', 'create', 'view', 'browse');

$testreport = new testreportTest();

r($testreport->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 edit 方法是否可以点击
r($testreport->isClickableTest($object, $actionList[1])) && p() && e('1'); // 测试 delete 方法是否可以点击
r($testreport->isClickableTest($object, $actionList[2])) && p() && e('1'); // 测试 create 方法是否可以点击
r($testreport->isClickableTest($object, $actionList[3])) && p() && e('1'); // 测试 view 方法是否可以点击
r($testreport->isClickableTest($object, $actionList[4])) && p() && e('1'); // 测试 browse 方法是否可以点击
