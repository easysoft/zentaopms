#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getAllModulePairs();
timeout=0
cid=19362

- 测试获取默认类型task的模块数量 @46
- 测试获取Bug类型的模块数量 @51
- 测试获取Case类型的模块数量 @41
- 测试获取Task类型的模块数量 @46
- 测试获取Story类型的模块数量 @26

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(100);

$type = array('bug', 'case', 'task');

$tree = new treeTest();

r($tree->getAllModulePairsTest())        && p() && e('46'); // 测试获取默认类型task的模块数量
r($tree->getAllModulePairsTest('bug'))   && p() && e('51'); // 测试获取Bug类型的模块数量
r($tree->getAllModulePairsTest('case'))  && p() && e('41'); // 测试获取Case类型的模块数量
r($tree->getAllModulePairsTest('task'))  && p() && e('46'); // 测试获取Task类型的模块数量
r($tree->getAllModulePairsTest('story')) && p() && e('26'); // 测试获取Story类型的模块数量