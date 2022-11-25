#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/ci.class.php';
su('admin');

/**

title=测试 ciModel->setMenu();
cid=1
pid=1

替换devops模块导航的参数 >> 代码|repo|browse|repoID=1

*/

$ci = new ciTest();

r($ci->setMenuTest()) && p('link') && e('代码|repo|browse|repoID=1'); //替换devops模块导航的参数