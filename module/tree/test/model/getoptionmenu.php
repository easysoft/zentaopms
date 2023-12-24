#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getOptionMenu();
timeout=0
cid=1

- 测试获取产品1 type story 的模块列表 @/
- 测试获取产品2 type story 的模块列表属性47 @/模块47
- 测试获取项目1 type task  的模块列表属性1 @/模块1
- 测试获取产品1 type task  的模块列表，包括story的模块2属性2 @/模块2
- 测试获取产品1 type task  的模块列表，包括story的模块2属性2 @/模块2
- 测试获取项目1 type task  的模块列表属性35 @/模块35
- 测试获取多分支产品41 type story 的模块列表属性37 @/主干/模块37

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('product')->gen(50);
zdTable('branch')->gen(10);
zdTable('module')->config('module')->gen(100);

$root = array(1, 2, 41, 42);

$tree = new treeTest();

r($tree->getOptionMenuTest(1))         && p(0)  && e('/');              // 测试获取产品1 type story 的模块列表
r($tree->getOptionMenuTest(2))         && p(47) && e('/模块47');        // 测试获取产品2 type story 的模块列表
r($tree->getOptionMenuTest(1, 'task')) && p(1)  && e('/模块1');         // 测试获取项目1 type task  的模块列表
r($tree->getOptionMenuTest(1, 'bug'))  && p(2)  && e('/模块2');         // 测试获取产品1 type task  的模块列表，包括story的模块2
r($tree->getOptionMenuTest(1, 'case')) && p(2)  && e('/模块2');         // 测试获取产品1 type task  的模块列表，包括story的模块2
r($tree->getOptionMenuTest(1, 'case')) && p(35) && e('/模块35');        // 测试获取项目1 type task  的模块列表
r($tree->getOptionMenuTest(41))        && p(37) && e('/主干/模块37');   // 测试获取多分支产品41 type story 的模块列表
