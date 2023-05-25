#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';
su('admin');

/**

title=测试 ciModel->setMenu();
cid=1
pid=1

替换devops模块导航的参数 >> 代码|repo|browse|repoID=1

*/

$ci = new ciTest();

r($ci->setMenuTest()) && p('link') && e('代码|repo|browse|repoID=1'); //替换devops模块导航的参数