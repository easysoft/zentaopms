#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getStoryModule();
cid=1
pid=1

测试获取module 1821 的story模块 >> 1821
测试获取module 1822 的story模块 >> 1822
测试获取module 1982 的story模块 >> 1982
测试获取module 1983 的story模块 >> 1983
测试获取module 2621 的story模块 >> 2621
测试获取module 2622 的story模块 >> 2622
测试获取module 21 的story模块 >> 0
测试获取module 3021 的story模块 >> 0

*/
$moduleID = array(1821, 1822, 1982, 1983, 2621, 2622, 21, 3021);

$tree = new treeTest();

r($tree->getStoryModuleTest($moduleID[0])) && p() && e('1821'); // 测试获取module 1821 的story模块
r($tree->getStoryModuleTest($moduleID[1])) && p() && e('1822'); // 测试获取module 1822 的story模块
r($tree->getStoryModuleTest($moduleID[2])) && p() && e('1982'); // 测试获取module 1982 的story模块
r($tree->getStoryModuleTest($moduleID[3])) && p() && e('1983'); // 测试获取module 1983 的story模块
r($tree->getStoryModuleTest($moduleID[4])) && p() && e('2621'); // 测试获取module 2621 的story模块
r($tree->getStoryModuleTest($moduleID[5])) && p() && e('2622'); // 测试获取module 2622 的story模块
r($tree->getStoryModuleTest($moduleID[6])) && p() && e('0');    // 测试获取module 21 的story模块
r($tree->getStoryModuleTest($moduleID[7])) && p() && e('0');    // 测试获取module 3021 的story模块