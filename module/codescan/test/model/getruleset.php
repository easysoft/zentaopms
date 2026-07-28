#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getRuleset();
timeout=0
cid=0

- 获取 1 号规则集详情 @edit1,enabled
- 获取 2 号规则集详情 @test2,enabled
- 获取 0 号规则集详情失败 @0
- 获取 3 号规则集详情失败 @0
- 获取 4 号规则集详情失败 @0

*/

$test = new codescanModelTest();

r($test->getRulesetTest(1)) && p('name,status') && e('edit1,enabled');
r($test->getRulesetTest(2)) && p('name,status') && e('test2,enabled');
r($test->getRulesetTest(0)) && p() && e('0');
r($test->getRulesetTest(3)) && p() && e('0');
r($test->getRulesetTest(4)) && p() && e('0');
