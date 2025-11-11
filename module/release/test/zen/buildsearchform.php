#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildSearchForm();
timeout=0
cid=0

- 测试正常产品类型queryID为0 >> queryID应为0
- 测试正常产品类型queryID为5 >> queryID和actionURL应正确设置
- 测试branch产品类型 >> 应有分支配置
- 测试platform产品类型 >> 应有分支配置
- 测试正常产品类型 >> 不应有分支配置

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

zendata('product')->loadYaml('buildsearchform', false, 2)->gen(10);
zendata('branch')->loadYaml('buildsearchform', false, 2)->gen(10);
zendata('build')->loadYaml('buildsearchform', false, 2)->gen(20);

su('admin');

$releaseTest = new releaseZenTest();

$normalProduct = new stdClass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdClass();
$branchProduct->id = 5;
$branchProduct->type = 'branch';

$platformProduct = new stdClass();
$platformProduct->id = 8;
$platformProduct->type = 'platform';

r($releaseTest->buildSearchFormTest(0, '', $normalProduct, '')) && p('queryID') && e('0');
r($releaseTest->buildSearchFormTest(5, '/release/browse-1-all-0.html', $normalProduct, '')) && p('queryID,actionURL') && e('5,/release/browse-1-all-0.html');
r($releaseTest->buildSearchFormTest(0, '', $branchProduct, '1')) && p('hasBranchConfig') && e('1');
r($releaseTest->buildSearchFormTest(0, '', $platformProduct, '2')) && p('hasBranchConfig') && e('1');
r($releaseTest->buildSearchFormTest(0, '', $normalProduct, '')) && p('hasBranchConfig') && e('0');
