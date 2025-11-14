#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildLinkStorySearchForm();
timeout=0
cid=18022

- 执行releaseTest模块的buildLinkStorySearchFormTest方法，参数是$normalRelease, 0 第actionURL条的contains属性 @true
- 执行releaseTest模块的buildLinkStorySearchFormTest方法，参数是$multiRelease, 1 属性branchConfigured @true
- 执行releaseTest模块的buildLinkStorySearchFormTest方法，参数是$normalRelease, 0 属性queryID @0
- 执行releaseTest模块的buildLinkStorySearchFormTest方法，参数是$normalRelease, 5 属性queryID @5
- 执行releaseTest模块的buildLinkStorySearchFormTest方法，参数是$normalRelease, 1 属性configComplete @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

zenData('product')->loadYaml('product_buildlinkstorysearchform', false, 2)->gen(5);
zenData('branch')->loadYaml('branch_buildlinkstorysearchform', false, 2)->gen(3);
zenData('productplan')->loadYaml('productplan_buildlinkstorysearchform', false, 2)->gen(5);
zenData('module')->loadYaml('module_buildlinkstorysearchform', false, 2)->gen(10);

su('admin');

$releaseTest = new releaseZenTest();

$normalRelease = new stdClass();
$normalRelease->id = 1;
$normalRelease->product = 1;
$normalRelease->branch = '0';
$normalRelease->productType = 'normal';

$multiRelease = new stdClass();
$multiRelease->id = 2;
$multiRelease->product = 2;
$multiRelease->branch = '1,2';
$multiRelease->productType = 'branch';

r($releaseTest->buildLinkStorySearchFormTest($normalRelease, 0)) && p('actionURL:contains') && e('true');
r($releaseTest->buildLinkStorySearchFormTest($multiRelease, 1)) && p('branchConfigured') && e('true');
r($releaseTest->buildLinkStorySearchFormTest($normalRelease, 0)) && p('queryID') && e('0');
r($releaseTest->buildLinkStorySearchFormTest($normalRelease, 5)) && p('queryID') && e('5');
r($releaseTest->buildLinkStorySearchFormTest($normalRelease, 1)) && p('configComplete') && e('true');