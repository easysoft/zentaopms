#!/usr/bin/env php
<?php

/**

title=测试 storyModel->parseExtra();
cid=18655

- 不传入任何数据 @0
- 传入项目 ID。属性projectID @1
- 传入项目和Bug ID，用逗号连接。
 - 属性projectID @1
 - 属性bugID @2
- 传入项目和Bug ID，用&连接。
 - 属性projectID @1
 - 属性bugID @2

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->parseExtra('')) && p() && e('0'); //不传入任何数据
r($storyModel->parseExtra('projectID=1'))         && p('projectID') && e('1');         //传入项目 ID。
r($storyModel->parseExtra('projectID=1,bugID=2')) && p('projectID,bugID') && e('1,2'); //传入项目和Bug ID，用逗号连接。
r($storyModel->parseExtra('projectID=1&bugID=2')) && p('projectID,bugID') && e('1,2'); //传入项目和Bug ID，用&连接。
