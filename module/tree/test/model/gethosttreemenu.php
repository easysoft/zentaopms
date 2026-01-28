#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getHostTreeMenu();
timeout=0
cid=19369

- 测试获取Host模块
 - 第0条的id属性 @1
 - 第0条的name属性 @这是一个模块1
 - 第0条的url属性 @host-browse-bymodule-1.html
- 测试获取Host模块
 - 第1条的id属性 @2
 - 第1条的name属性 @这是一个模块2
 - 第1条的url属性 @host-browse-bymodule-2.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$module = zenData('module');
$module->type->range('host');
$module->parent->range('0,1');
$module->path->range(',1,`,1,2,`');
$module->gen(2);

$tree = new treeModelTest();

r($tree->getHostTreeMenuTest())  && p('0:id,name,url') && e('1,这是一个模块1,host-browse-bymodule-1.html');  // 测试获取Host模块
r($tree->getHostTreeMenuTest())  && p('1:id,name,url') && e('2,这是一个模块2,host-browse-bymodule-2.html');  // 测试获取Host模块