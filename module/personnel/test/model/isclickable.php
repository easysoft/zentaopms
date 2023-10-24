#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

/**

title=测试 personnelModel->isClickable();
cid=1
pid=1

测试 unbindWhitelist 方法是否可以点击 >> 1

*/

$object     = new stdclass();
$actionList = array('unbindWhitelist');

$personnel = new personnelTest('admin');

r($personnel->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 unbindWhitelist 方法是否可以点击
