#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createBugLink();
timeout=0
cid=19347

- 测试创建module 4  的buglink属性url @bug-browse-1--byModule-4.html
- 测试创建module 20 的buglink属性url @bug-browse-41--byModule-20.html
- 测试创建module 4, tab=project, type=closed 的buglink属性url @project-bug-0-1-0--0-closed-4.html
- 测试创建module 4, tab=project, branchID=2 的buglink属性url @project-bug-0-1-2--0-all-4.html
- 测试创建module 4, tab=execution, orderBy=title_desc的buglink属性url @execution-bug-0-1-0-title_desc-0-all-4.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(20);

global $app;
$tree = new treeModelTest();

$extra1 = array('type' => 'closed');
$extra2 = array('branchID' => 2);
$extra3 = array('orderBy' => 'title_desc');

r($tree->createBugLinkTest(4))  && p('url') && e("createbuglink.php?m=bug&f=browse&root=1&branch=&type=byModule&param=4");   // 测试创建module 4  的buglink
r($tree->createBugLinkTest(20)) && p('url') && e("createbuglink.php?m=bug&f=browse&root=41&branch=&type=byModule&param=20"); // 测试创建module 20 的buglink

r($tree->createBugLinkTest(4, 'project',   $extra1)) && p('url') && e("createbuglink.php?m=project&f=bug&projectID=0&productID=1&branch=0&orderBy=&build=0&type=closed&param=4");          // 测试创建module 4, tab=project, type=closed 的buglink
r($tree->createBugLinkTest(4, 'project',   $extra2)) && p('url') && e("createbuglink.php?m=project&f=bug&projectID=0&productID=1&branch=2&orderBy=&build=0&type=all&param=4");             // 测试创建module 4, tab=project, branchID=2 的buglink
r($tree->createBugLinkTest(4, 'execution', $extra3)) && p('url') && e("createbuglink.php?m=execution&f=bug&executionID=0&productID=1&branch=0&orderBy=title_desc&build=0&type=all&param=4"); // 测试创建module 4, tab=execution, orderBy=title_desc的buglink
