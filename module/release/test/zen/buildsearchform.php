#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildSearchForm();
timeout=0
cid=0

- 执行releaseTest模块的buildSearchFormTest方法，参数是1, '/release-browse-1.html', $normalProduct, '0'
 - 属性queryID @1
 - 属性actionURL @/release-browse-1.html
 - 属性hasBranchValues @0
 - 属性productType @normal
- 执行releaseTest模块的buildSearchFormTest方法，参数是2, '/release-browse-2.html', $branchProduct, 'all'
 - 属性queryID @2
 - 属性actionURL @/release-browse-2.html
 - 属性hasBranchValues @1
 - 属性productType @branch
- 执行releaseTest模块的buildSearchFormTest方法，参数是0, '/release-browse-0.html', $platformProduct, 'trunk'
 - 属性queryID @0
 - 属性actionURL @/release-browse-0.html
 - 属性hasBranchValues @1
 - 属性productType @platform
- 执行releaseTest模块的buildSearchFormTest方法，参数是999, '', $normalProduct, '1'
 - 属性queryID @999
 - 属性actionURL @~~
 - 属性hasBranchValues @0
 - 属性productType @normal
- 执行releaseTest模块的buildSearchFormTest方法，参数是5, '/release-browse-5.html', $branchProduct, ''
 - 属性queryID @5
 - 属性actionURL @/release-browse-5.html
 - 属性hasBranchValues @1
 - 属性productType @branch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('branch')->loadYaml('branch', false, 2)->gen(5);
zendata('build')->loadYaml('build', false, 2)->gen(20);

su('admin');

$releaseTest = new releaseTest();

/* Create test product objects */
$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';
$normalProduct->name = 'Normal Product';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';
$branchProduct->name = 'Branch Product';

$platformProduct = new stdclass();
$platformProduct->id = 3;
$platformProduct->type = 'platform';
$platformProduct->name = 'Platform Product';

r($releaseTest->buildSearchFormTest(1, '/release-browse-1.html', $normalProduct, '0')) && p('queryID,actionURL,hasBranchValues,productType') && e('1,/release-browse-1.html,0,normal');
r($releaseTest->buildSearchFormTest(2, '/release-browse-2.html', $branchProduct, 'all')) && p('queryID,actionURL,hasBranchValues,productType') && e('2,/release-browse-2.html,1,branch');
r($releaseTest->buildSearchFormTest(0, '/release-browse-0.html', $platformProduct, 'trunk')) && p('queryID,actionURL,hasBranchValues,productType') && e('0,/release-browse-0.html,1,platform');
r($releaseTest->buildSearchFormTest(999, '', $normalProduct, '1')) && p('queryID,actionURL,hasBranchValues,productType') && e('999,~~,0,normal');
r($releaseTest->buildSearchFormTest(5, '/release-browse-5.html', $branchProduct, '')) && p('queryID,actionURL,hasBranchValues,productType') && e('5,/release-browse-5.html,1,branch');