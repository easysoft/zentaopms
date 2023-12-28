#!/usr/bin/env php
<?php

/**

title=测试 treeModel->checkUnique();
timeout=0
cid=1

- 测试module1是否与已存在的模块重名 @0
- 测试module2是否与已存在的模块重名 @模块2
- 测试与数据库已有模块重名 @模块2
- 测试本身重名的模块列表 @模块a1
- 测试与数据库已有模块重名，但是不在一个分支 @0
- 测试与数据库已有模块重名，在一个分支 @模块2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(100);

$module1 = new stdclass();
$module1->name   = '不会重名的模块';
$module1->root   = 1;
$module1->branch = '0';
$module1->type   = 'story';
$module1->parent = '0';

$module2 = new stdclass();
$module2->name   = '模块2';
$module2->root   = 1;
$module2->branch = '0';
$module2->type   = 'story';
$module2->parent = '0';

$modules1  = array('模块2');
$modules2  = array('模块a1', '模块a1');
$branches1 = array('1', '2');
$branches2 = array('0', '0');
$branches3 = array('1', '2');

$tree = new treeTest();

r($tree->checkUniqueTest($module1))                       && p() && e('0');       // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module2))                       && p() && e('模块2');   // 测试module2是否与已存在的模块重名
r($tree->checkUniqueTest($module1, $modules1))            && p() && e('模块2');   // 测试与数据库已有模块重名
r($tree->checkUniqueTest($module1, $modules2))            && p() && e('模块a1');  // 测试本身重名的模块列表
r($tree->checkUniqueTest($module1, $modules1, $branches1)) && p() && e('0');      // 测试与数据库已有模块重名，但是不在一个分支
r($tree->checkUniqueTest($module1, $modules1, $branches2)) && p() && e('模块2');  // 测试与数据库已有模块重名，在一个分支