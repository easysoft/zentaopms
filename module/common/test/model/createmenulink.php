#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::createMenuLink();
timeout=0
cid=1

- 查看拼接后的导航链接 @-createmenulink.php?m=task&f=create&executionID=1
- 查看拼接后的导航链接 @-createmenulink.php?m=execution&f=browse&id=1

*/

global $tester, $config;

$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

$menuLink = new stdclass();
$menuLink->link['module'] = 'task';
$menuLink->link['method'] = 'create';
$menuLink->link['vars']   = 'executionID=1';

$menuLink1 = new stdclass();
$menuLink1->link['module'] = 'execution';
$menuLink1->link['method'] = 'browse';
$menuLink1->link['vars']   = 'id=1';

$link1 = $tester->loadModel('common')->createMenuLink($menuLink);
$link2 = $tester->loadModel('common')->createMenuLink($menuLink1);

r($link1) && p('') && e('task-create-1.html'); // 查看拼接后的导航链接
r($link2) && p('') && e('execution-browse-1.html');     // 查看拼接后的导航链接
