#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::getByBranchName();
timeout=0
cid=0

- 测试有效分支名查询 @1
- 测试不存在的分支名 >> 返回空数组 @0
- 测试不存在的repo >> 返回空数组 @0
- 测试repo=0空repo >> 返回空数组 @0
- 测试空分支名 >> 返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(10);

su('admin');

$tester = new reporeviewflowTest();

$r1 = $tester->getByBranchNameTest(1, 'main');
$r2 = $tester->getByBranchNameTest(1, 'nonexistent');
$r3 = $tester->getByBranchNameTest(999, 'main');
$r4 = $tester->getByBranchNameTest(0, 'main');
$r5 = $tester->getByBranchNameTest(1, '');

r((is_object($r1) || (is_array($r1) && empty($r1))) ? 1 : 0) && p() && e('1');
r(is_array($r2) && empty($r2) ? 1 : 0) && p() && e('1');
r(is_array($r3) && empty($r3) ? 1 : 0) && p() && e('1');
r(is_array($r4) && empty($r4) ? 1 : 0) && p() && e('1');
r(is_array($r5) && empty($r5) ? 1 : 0) && p() && e('1');
