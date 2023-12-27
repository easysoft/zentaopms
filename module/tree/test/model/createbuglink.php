#!/usr/bin/env php
<?php

/**

title=测试 treeModel->createBugLink();
timeout=0
cid=1

- 测试创建module 4  的buglink属性url @bug-browse-1--byModule-4.html
- 测试创建module 20 的buglink属性url @bug-browse-41--byModule-20.html
- 测试创建module 4, tab=project, type=closed 的buglink属性url @project-bug-0-1-0--0-closed-4.html
- 测试创建module 4, tab=project, branchID=2 的buglink属性url @project-bug-0-1-2--0-all-4.html
- 测试创建module 4, tab=execution, orderBy=title_desc的buglink属性url @execution-bug-0-1-0-title_desc-0-all-4.html

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

global $app;
$tree = new treeTest();

$extra1 = array('type' => 'closed');
$extra2 = array('branchID' => 2);
$extra3 = array('orderBy' => 'title_desc');

r($tree->createBugLinkTest(4))  && p('url') && e("bug-browse-1--byModule-4.html");   // 测试创建module 4  的buglink
r($tree->createBugLinkTest(20)) && p('url') && e("bug-browse-41--byModule-20.html"); // 测试创建module 20 的buglink

r($tree->createBugLinkTest(4, 'project',   $extra1)) && p('url') && e("project-bug-0-1-0--0-closed-4.html");          // 测试创建module 4, tab=project, type=closed 的buglink
r($tree->createBugLinkTest(4, 'project',   $extra2)) && p('url') && e("project-bug-0-1-2--0-all-4.html");             // 测试创建module 4, tab=project, branchID=2 的buglink
r($tree->createBugLinkTest(4, 'execution', $extra3)) && p('url') && e("execution-bug-0-1-0-title_desc-0-all-4.html"); // 测试创建module 4, tab=execution, orderBy=title_desc的buglink