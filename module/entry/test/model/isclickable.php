#!/usr/bin/env php
<?php

/**

title=测试 entryModel::isClickable();
timeout=0
cid=1

sed: can't read /home/xieqiyu/repo/zentaopms/test/config/my.php: No such file or directory
- 测试 log 方法是否可以点击 @1
- 测试 edit 方法是否可以点击 @1
- 测试 delete 方法是否可以点击 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

$object     = new stdclass();
$actionList = array('log', 'edit', 'delete');

$entry = new entryTest();

r($entry->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 log 方法是否可以点击
r($entry->isClickableTest($object, $actionList[1])) && p() && e('1'); // 测试 edit 方法是否可以点击
r($entry->isClickableTest($object, $actionList[2])) && p() && e('1'); // 测试 delete 方法是否可以点击