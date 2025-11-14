#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createTaskLink();
timeout=0
cid=19358

- 测试获取module 1  的task链接属性url @execution-task-1-byModule-1.html
- 测试获取module 2  的task链接属性url @execution-task-1-byModule-2.html
- 测试获取module 3  的task链接属性url @execution-task-1-byModule-3.html
- 测试获取module 4  的task链接属性url @execution-task-1-byModule-4.html
- 测试获取module 18 的task链接属性url @execution-task-41-byModule-18.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(20);

$moduleID    = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);
$productID   = array(1, 2);
$executionID = array(101, 102);

$tree = new treeTest();

r($tree->createTaskLinkTest(1))  && p('url') && e("execution-task-1-byModule-1.html");   // 测试获取module 1  的task链接
r($tree->createTaskLinkTest(2))  && p('url') && e("execution-task-1-byModule-2.html");   // 测试获取module 2  的task链接
r($tree->createTaskLinkTest(3))  && p('url') && e("execution-task-1-byModule-3.html");   // 测试获取module 3  的task链接
r($tree->createTaskLinkTest(4))  && p('url') && e("execution-task-1-byModule-4.html");   // 测试获取module 4  的task链接
r($tree->createTaskLinkTest(18)) && p('url') && e("execution-task-41-byModule-18.html"); // 测试获取module 18 的task链接