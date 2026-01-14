#!/usr/bin/env php
<?php

/**

title=测试 commonModel::createMenuLink();
timeout=0
cid=15667

- 测试普通数组链接创建 @task-create-1.html
- 测试tutorial模式链接创建 @tutorial-wizard-project-browse-c3RhdHVzPWFjdGl2ZQ==.html
- 测试非数组直接链接 @https://www.zentao.net
- 测试vars参数为空的数组链接 @user-browse.html
- 测试包含特殊字符的vars参数 @bug-browse-1-active-bug.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester, $config;

$config->webRoot     = '';
$config->requestType = 'PATH_INFO';

su('admin');

$commonTest = new commonModelTest();

// 测试数据1：普通数组链接
$menuItem1 = new stdclass();
$menuItem1->link = array();
$menuItem1->link['module'] = 'task';
$menuItem1->link['method'] = 'create';
$menuItem1->link['vars'] = 'executionID=1';

// 测试数据2：tutorial模式链接
$menuItem2 = new stdclass();
$menuItem2->link = array();
$menuItem2->link['module'] = 'project';
$menuItem2->link['method'] = 'browse';
$menuItem2->link['vars'] = 'status=active';
$menuItem2->tutorial = true;

// 测试数据3：非数组直接链接
$menuItem3 = new stdclass();
$menuItem3->link = 'https://www.zentao.net';

// 测试数据4：vars参数为空的数组链接
$menuItem4 = new stdclass();
$menuItem4->link = array();
$menuItem4->link['module'] = 'user';
$menuItem4->link['method'] = 'browse';
$menuItem4->link['vars'] = '';

// 测试数据5：包含特殊字符的vars参数
$menuItem5 = new stdclass();
$menuItem5->link = array();
$menuItem5->link['module'] = 'bug';
$menuItem5->link['method'] = 'browse';
$menuItem5->link['vars'] = 'productID=1&status=active&type=bug';

r($commonTest->createMenuLinkTest($menuItem1)) && p() && e('task-create-1.html'); // 测试普通数组链接创建
r($commonTest->createMenuLinkTest($menuItem2)) && p() && e('tutorial-wizard-project-browse-c3RhdHVzPWFjdGl2ZQ==.html'); // 测试tutorial模式链接创建
r($commonTest->createMenuLinkTest($menuItem3)) && p() && e('https://www.zentao.net'); // 测试非数组直接链接
r($commonTest->createMenuLinkTest($menuItem4)) && p() && e('user-browse.html'); // 测试vars参数为空的数组链接
r($commonTest->createMenuLinkTest($menuItem5)) && p() && e('bug-browse-1-active-bug.html'); // 测试包含特殊字符的vars参数