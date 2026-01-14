#!/usr/bin/env php
<?php
/**

title=测试 testsuiteModel->isClickable();
cid=19148

- 测试 browse 方法是否可以点击 @1
- 测试 create 方法是否可以点击 @1
- 测试 view 方法是否可以点击 @1
- 测试 linkCase 方法是否可以点击 @1
- 测试 edit 方法是否可以点击 @1
- 测试 delete 方法是否可以点击 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$object     = new stdclass();
$actionList = array('browse', 'create', 'view', 'linkCase', 'edit', 'delete');

su('admin');
$testsuite = new testsuiteModelTest();
r($testsuite->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 browse 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[1])) && p() && e('1'); // 测试 create 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[2])) && p() && e('1'); // 测试 view 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[3])) && p() && e('1'); // 测试 linkCase 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[4])) && p() && e('1'); // 测试 edit 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[5])) && p() && e('1'); // 测试 delete 方法是否可以点击
