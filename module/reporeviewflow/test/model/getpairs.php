#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::getPairs();
timeout=0
cid=0

- 测试repo=1获取键值对 @1
- 测试repo=2获取键值对 @1
- 测试repo=999不存在 >> 返回空 @0
- 测试status=enable过滤 @1
- 测试status=disable过滤 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(10);

su('admin');

$tester = new reporeviewflowTest();

r(count($tester->getPairsTest(1))) && p() && e('1');
r(count($tester->getPairsTest(2))) && p() && e('1');
r(count($tester->getPairsTest(999))) && p() && e('0');
r(count($tester->getPairsTest(1, 'enable'))) && p() && e('1');
r(count($tester->getPairsTest(1, 'disable'))) && p() && e('0');
