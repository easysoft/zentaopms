#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';
su('admin');

zenData('action')->loadYaml('action')->gen(3);
zenData('actionrecent')->gen(0);

/**

title=测试 actionModel->hideAll();
timeout=0
cid=14916

- 隐藏回收站全部信息，并获取隐藏的产品信息
 - 第0条的id属性 @1
 - 第0条的extra属性 @2
- 隐藏回收站全部信息，并获取隐藏的需求信息
 - 第1条的id属性 @2
 - 第1条的extra属性 @2
- 隐藏回收站全部信息，并获取隐藏的计划信息
 - 第2条的id属性 @3
 - 第2条的extra属性 @2

*/

$action = new actionTest();

$result = $action->hideAllTest();
r($result) && p('0:id,extra') && e('1,2'); // 隐藏回收站全部信息，并获取隐藏的产品信息
r($result) && p('1:id,extra') && e('2,2'); // 隐藏回收站全部信息，并获取隐藏的需求信息
r($result) && p('2:id,extra') && e('3,2'); // 隐藏回收站全部信息，并获取隐藏的计划信息
