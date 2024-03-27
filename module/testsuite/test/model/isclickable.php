#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testsuite.class.php';

/**

title=测试 testsuiteModel->isClickable();
cid=1
pid=1

测试 linkcase 方法是否可以点击 >> 1
测试 edit 方法是否可以点击     >> 1
测试 delete 方法是否可以点击   >> 1

*/

$object     = new stdclass();
$actionList = array('linkCase', 'edit', 'delete');

su('admin');
$testsuite = new testsuiteTest();
r($testsuite->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 linkCase 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[1])) && p() && e('1'); // 测试 edit 方法是否可以点击
r($testsuite->isClickableTest($object, $actionList[2])) && p() && e('1'); // 测试 delete 方法是否可以点击

