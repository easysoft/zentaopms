#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getAllChildId();
timeout=0
cid=1

- 测试获取module 1 的全部子项 @,1,6,26

- 测试获取module 2 的全部子项 @,2,7,27

- 测试获取module 3 的全部子项 @,3,8,28

- 测试获取module 4 的全部子项 @,4,9,29

- 测试获取module 5 的全部子项 @,5,10,30

- 测试获取module 6 的全部子项 @,6

- 测试获取module 7 的全部子项 @,7

- 测试获取module 8 的全部子项 @,8

- 测试获取module 9 的全部子项 @,9

- 测试获取module 10 的全部子项 @,10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(30);

$moduleID = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

$tree = new treeTest();

r($tree->getAllChildIdTest($moduleID[0])) && p() && e(',1,6,26');  // 测试获取module 1 的全部子项
r($tree->getAllChildIdTest($moduleID[1])) && p() && e(',2,7,27');  // 测试获取module 2 的全部子项
r($tree->getAllChildIdTest($moduleID[2])) && p() && e(',3,8,28');  // 测试获取module 3 的全部子项
r($tree->getAllChildIdTest($moduleID[3])) && p() && e(',4,9,29');  // 测试获取module 4 的全部子项
r($tree->getAllChildIdTest($moduleID[4])) && p() && e(',5,10,30'); // 测试获取module 5 的全部子项
r($tree->getAllChildIdTest($moduleID[5])) && p() && e(',6');       // 测试获取module 6 的全部子项
r($tree->getAllChildIdTest($moduleID[6])) && p() && e(',7');       // 测试获取module 7 的全部子项
r($tree->getAllChildIdTest($moduleID[7])) && p() && e(',8');       // 测试获取module 8 的全部子项
r($tree->getAllChildIdTest($moduleID[8])) && p() && e(',9');       // 测试获取module 9 的全部子项
r($tree->getAllChildIdTest($moduleID[9])) && p() && e(',10');      // 测试获取module 10 的全部子项