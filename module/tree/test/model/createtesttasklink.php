#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createTestTaskLink();
timeout=0
cid=1

- 测试创建module 1 的TestTasklink属性url @testtask-cases-1-byModule-1.html
- 测试创建module 2 的TestTasklink属性url @testtask-cases-1-byModule-2.html
- 测试创建module 3 的TestTasklink属性url @testtask-cases-1-byModule-3.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

$extra = 1;

$tree = new treeTest();

r($tree->createTestTaskLinkTest(1, $extra)) && p('url') && e("testtask-cases-1-byModule-1.html"); // 测试创建module 1 的TestTasklink
r($tree->createTestTaskLinkTest(2, $extra)) && p('url') && e("testtask-cases-1-byModule-2.html"); // 测试创建module 2 的TestTasklink
r($tree->createTestTaskLinkTest(3, $extra)) && p('url') && e("testtask-cases-1-byModule-3.html"); // 测试创建module 3 的TestTasklink