#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getByID();
timeout=0
cid=1

- 测试获取不存在模块的信息
 - 属性root @0
 - 属性name @0
 - 属性short @0
- 测试获取模块1的信息
 - 属性root @1
 - 属性name @模块1
 - 属性short @模块简称1
- 测试获取模块10的信息
 - 属性root @1
 - 属性name @模块10
 - 属性short @模块简称10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

zdTable('module')->config('module')->gen(100);

su('admin');

$tree = new treeTest();

r($tree->getByIDTest(0))  && p('root,name,short') && e('0,0,0');               // 测试获取不存在模块的信息
r($tree->getByIDTest(1))  && p('root,name,short') && e('1,模块1,模块简称1');   // 测试获取模块1的信息
r($tree->getByIDTest(10)) && p('root,name,short') && e('1,模块10,模块简称10'); // 测试获取模块10的信息