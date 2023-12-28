#!/usr/bin/env php
<?php

/**

title=测试 treeModel->delete();
timeout=0
cid=1

- 测试删除module 0属性deleted @0
- 测试删除module 1属性deleted @1
- 测试删除module 2属性deleted @1
- 测试删除module 3属性deleted @1
- 测试删除module 4属性deleted @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

ob_start();
r($tree->removeTest(0)) && p('deleted') && e('0'); // 测试删除module 0
r($tree->removeTest(1)) && p('deleted') && e('1'); // 测试删除module 1
r($tree->removeTest(2)) && p('deleted') && e('1'); // 测试删除module 2
r($tree->removeTest(3)) && p('deleted') && e('1'); // 测试删除module 3
r($tree->removeTest(4)) && p('deleted') && e('1'); // 测试删除module 4
ob_end_flush();