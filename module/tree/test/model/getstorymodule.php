#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getStoryModule();
timeout=0
cid=1

- 测试获取module 2 的story模块 @2
- 测试获取module 7 的story模块 @7
- 测试获取非story module 6 的story模块 @0
- 测试获取不存在 module 10 的story模块 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$moduleID = array(2, 7, 6, 30);

$tree = new treeTest();

r($tree->getStoryModuleTest($moduleID[0])) && p() && e('2'); // 测试获取module 2 的story模块
r($tree->getStoryModuleTest($moduleID[1])) && p() && e('7'); // 测试获取module 7 的story模块
r($tree->getStoryModuleTest($moduleID[2])) && p() && e('0'); // 测试获取非story module 6 的story模块
r($tree->getStoryModuleTest($moduleID[3])) && p() && e('0'); // 测试获取不存在 module 10 的story模块