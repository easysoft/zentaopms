#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createCaseLibLink();
timeout=0
cid=19348

- 测试创建module 1 的 caselib link属性url @caselib-browse-1-byModule-1.html
- 测试创建module 2 的 caselib link属性url @caselib-browse-1-byModule-2.html
- 测试创建module 3 的 caselib link属性url @caselib-browse-1-byModule-3.html
- 测试创建module 4 的 caselib link属性url @caselib-browse-1-byModule-4.html
- 测试创建module 5 的 caselib link属性url @caselib-browse-1-byModule-5.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$tree = new treeModelTest();

r($tree->createCaseLibLinkTest(1)) && p('url') && e("caselib-browse-1-byModule-1.html"); // 测试创建module 1 的 caselib link
r($tree->createCaseLibLinkTest(2)) && p('url') && e("caselib-browse-1-byModule-2.html"); // 测试创建module 2 的 caselib link
r($tree->createCaseLibLinkTest(3)) && p('url') && e("caselib-browse-1-byModule-3.html"); // 测试创建module 3 的 caselib link
r($tree->createCaseLibLinkTest(4)) && p('url') && e("caselib-browse-1-byModule-4.html"); // 测试创建module 4 的 caselib link
r($tree->createCaseLibLinkTest(5)) && p('url') && e("caselib-browse-1-byModule-5.html"); // 测试创建module 5 的 caselib link