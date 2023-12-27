#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createTaskLink();
timeout=0
cid=1

- 测试获取module 1  的task链接属性url @execution-task-1-byModule-1.html
- 测试获取module 18 的task链接属性url @execution-task-41-byModule-18.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$productID   = array(1, 2);
$executionID = array(101, 102);

$tree = new treeTest();

r($tree->createTaskLinkTest(1))  && p('url') && e("execution-task-1-byModule-1.html");   // 测试获取module 1  的task链接
r($tree->createTaskLinkTest(18)) && p('url') && e("execution-task-41-byModule-18.html"); // 测试获取module 18 的task链接