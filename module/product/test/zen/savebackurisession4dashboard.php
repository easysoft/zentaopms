#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveBackUriSession4Dashboard();
timeout=0
cid=17606

- 测试返回hasProductPlanList值为0属性hasProductPlanList @0
- 测试返回hasReleaseList值为0属性hasReleaseList @0
- 测试返回productPlanList为空属性productPlanList @~~
- 测试返回releaseList为空属性releaseList @~~
- 测试两个键都存在且值为0
 - 属性hasProductPlanList @0
 - 属性hasReleaseList @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->saveBackUriSession4DashboardTest()) && p('hasProductPlanList') && e('0'); // 测试返回hasProductPlanList值为0
r($productTest->saveBackUriSession4DashboardTest()) && p('hasReleaseList') && e('0'); // 测试返回hasReleaseList值为0
r($productTest->saveBackUriSession4DashboardTest()) && p('productPlanList') && e('~~'); // 测试返回productPlanList为空
r($productTest->saveBackUriSession4DashboardTest()) && p('releaseList') && e('~~'); // 测试返回releaseList为空
r($productTest->saveBackUriSession4DashboardTest()) && p('hasProductPlanList,hasReleaseList') && e('0,0'); // 测试两个键都存在且值为0