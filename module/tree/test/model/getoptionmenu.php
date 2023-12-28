#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getOptionMenu();
timeout=0
cid=1

- 测试获取产品1 type story 的模块列表 @/
- 测试获取产品1 type story 的模块列表属性2 @/模块2
- 测试获取项目1 type task  的模块列表属性1 @/模块1
- 测试获取产品1 type bug   的模块列表，包括story的模块2属性4 @/模块4
- 测试获取产品1 type case  的模块列表，包括story的模块2属性5 @/模块5
- 测试获取不存在产品 type case  的模块列表，包括根目录 @/
- 测试获取不存在产品 type case  的模块列表属性5 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('product')->gen(50);
zdTable('branch')->gen(10);
zdTable('module')->config('module')->gen(100);

$root = array(1, 2, 41, 42);

$tree = new treeTest();

r($tree->getOptionMenuTest(1))         && p(0) && e('/');      // 测试获取产品1 type story 的模块列表
r($tree->getOptionMenuTest(1))         && p(2) && e('/模块2'); // 测试获取产品1 type story 的模块列表
r($tree->getOptionMenuTest(1, 'task')) && p(1) && e('/模块1'); // 测试获取项目1 type task  的模块列表
r($tree->getOptionMenuTest(1, 'bug'))  && p(4) && e('/模块4'); // 测试获取产品1 type bug   的模块列表，包括story的模块2
r($tree->getOptionMenuTest(1, 'case')) && p(5) && e('/模块5'); // 测试获取产品1 type case  的模块列表，包括story的模块2
r($tree->getOptionMenuTest(5, 'case')) && p(0) && e('/');      // 测试获取不存在产品 type case  的模块列表，包括根目录
r($tree->getOptionMenuTest(5, 'case')) && p(5) && e('~~');     // 测试获取不存在产品 type case  的模块列表