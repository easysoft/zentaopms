#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildSearchForm();
timeout=0
cid=18024

- 执行releaseTest模块的buildSearchFormTest方法，参数是0, '', $normalProduct, '' 属性queryID @0
- 执行releaseTest模块的buildSearchFormTest方法，参数是5, '/release/browse-1-all-0.html', $normalProduct, ''
 - 属性queryID @5
 - 属性actionURL @/release/browse-1-all-0.html
- 执行releaseTest模块的buildSearchFormTest方法，参数是0, '', $branchProduct, '1' 属性hasBranchConfig @1
- 执行releaseTest模块的buildSearchFormTest方法，参数是0, '', $platformProduct, '2' 属性hasBranchConfig @1
- 执行releaseTest模块的buildSearchFormTest方法，参数是0, '', $normalProduct, '' 属性hasBranchConfig @0

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