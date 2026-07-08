#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createCaseLink();
timeout=0
cid=19349

- 测试创建module 4  的buglink属性url @testcase-browse-1-0-byModule-5.html
- 测试创建module 20 的buglink属性url @testcase-browse-1-0-byModule-10.html
- 测试创建module 5, tab=project, type=closed 的buglink属性url @project-testcase--1-all-byModule-5.html
- 测试创建module 5, tab=execution, orderBy=title_desc的buglink属性url @execution-testcase-1-1-all-byModule-0-5.html
- 测试创建module 5, tab=product, orderBy=title_desc的buglink属性url @testcase-browse-1-all-byModule-5.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(20);

global $app;
$tree = new treeModelTest();

$extra = array('projectID' => '1');

r($tree->createCaseLinkTest(5))  && p('url') && e("createcaselink.php?m=testcase&f=browse&productID=1&branch=0&browseType=byModule&param=5");   // 测试创建module 4  的buglink
r($tree->createCaseLinkTest(10)) && p('url') && e("createcaselink.php?m=testcase&f=browse&productID=1&branch=0&browseType=byModule&param=10"); // 测试创建module 20 的buglink

r($tree->createCaseLinkTest(5, 'project',   $extra)) && p('url') && e("createcaselink.php?m=project&f=testcase&projectID=&productID=1&branch=all&browseType=byModule&param=5");      // 测试创建module 5, tab=project, type=closed 的buglink
r($tree->createCaseLinkTest(5, 'execution', $extra)) && p('url') && e("createcaselink.php?m=execution&f=testcase&executionID=1&productID=1&branch=all&browseType=byModule&param=0&moduleID=5"); // 测试创建module 5, tab=execution, orderBy=title_desc的buglink
r($tree->createCaseLinkTest(5, 'product', $extra)) && p('url') && e("createcaselink.php?m=testcase&f=browse&productID=1&branch=all&browseType=byModule&param=5"); // 测试创建module 5, tab=product, orderBy=title_desc的buglink
