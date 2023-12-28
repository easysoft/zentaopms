#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createCaseLibLink();
timeout=0
cid=1

- 测试创建module 1 的 feedback link属性url @caselib-browse-1-byModule-1.html
- 测试创建module 2 的 feedback link属性url @caselib-browse-1-byModule-2.html
- 测试创建module 3 的 feedback link属性url @caselib-browse-1-byModule-3.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

$tree = new treeTest();

r($tree->createCaseLibLinkTest(1)) && p('url') && e("caselib-browse-1-byModule-1.html"); // 测试创建module 1 的 feedback link
r($tree->createCaseLibLinkTest(2)) && p('url') && e("caselib-browse-1-byModule-2.html"); // 测试创建module 2 的 feedback link
r($tree->createCaseLibLinkTest(3)) && p('url') && e("caselib-browse-1-byModule-3.html"); // 测试创建module 3 的 feedback link