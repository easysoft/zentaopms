#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('product')->gen(50);
zdTable('branch')->gen(10);
$module = zdTable('module');
$module->root->range('1-50');
$module->gen(100);

/**

title=测试 treeModel->buildTreeArray();
cid=1
pid=1

*/
$tree = new treeTest();

$treeMenu = array();

$modules = $tree->objectModel->dao->select('*')->from(TABLE_MODULE)->where('root')->eq(1)->fetchAll('id');
$modules[51]->parent = 1;
$modules[51]->path   = ',1,51,';
$modules[51]->grade  = 2;

r(trim($tree->buildTreeArrayTest($treeMenu, $modules, $modules[1])[0]))  && p('0') && e('/这是一个模块1|1');                 // 测试创建树选项 1
r(trim($tree->buildTreeArrayTest($treeMenu, $modules, $modules[51])[1])) && p('1') && e('/这是一个模块1/这是一个模块51|51'); // 测试创建树选项 51
