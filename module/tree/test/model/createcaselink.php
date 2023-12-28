#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createCaseLink();
timeout=0
cid=1

- 测试创建module 4  的buglink属性url @testcase-browse-1-0-byModule-5.html
- 测试创建module 20 的buglink属性url @testcase-browse-1-0-byModule-10.html
- 测试创建module 4, tab=project, type=closed 的buglink属性url @project-testcase--1-all-byModule-5.html
- 测试创建module 4, tab=execution, orderBy=title_desc的buglink属性url @execution-testcase-1-1-all-byModule-5.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

global $app;
$tree = new treeTest();

$extra = array('projectID' => '1');

r($tree->createCaseLinkTest(5))  && p('url') && e("testcase-browse-1-0-byModule-5.html");   // 测试创建module 4  的buglink
r($tree->createCaseLinkTest(10)) && p('url') && e("testcase-browse-1-0-byModule-10.html"); // 测试创建module 20 的buglink

r($tree->createCaseLinkTest(5, 'project',   $extra)) && p('url') && e("project-testcase--1-all-byModule-5.html");          // 测试创建module 4, tab=project, type=closed 的buglink
r($tree->createCaseLinkTest(5, 'execution', $extra)) && p('url') && e("execution-testcase-1-1-all-byModule-5.html"); // 测试创建module 4, tab=execution, orderBy=title_desc的buglink