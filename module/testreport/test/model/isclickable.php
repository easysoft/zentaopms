#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

/**

title=测试 testreportModel->isClickable();
cid=1
pid=1

正常新增 >> 11,正常新增
负责人为空测试 >> 『负责人』不能为空。
标题为空测试 >> 『标题』不能为空。
参与人员测试 >> 12

*/

$object     = new stdclass();
$actionList = array('edit', 'delete');

$testreport = new testreportTest();

r($testreport->isClickableTest($object, $actionList[0])) && p() && e('1'); // 测试 edit 方法是否可以点击
r($testreport->isClickableTest($object, $actionList[1])) && p() && e('1'); // 测试 delete 方法是否可以点击

