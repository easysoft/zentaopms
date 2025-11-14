#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveSession4Roadmap();
timeout=0
cid=17609

- 测试步骤1:调用saveSession4Roadmap方法,验证releaseList session值属性hasReleaseList @0
- 测试步骤2:验证productPlanList session值属性hasProductPlanList @0
- 测试步骤3:验证hasReleaseList返回值属性releaseListContains @0
- 测试步骤4:验证hasProductPlanList返回值属性productPlanListContains @0
- 测试步骤5:多次调用验证session值保持一致
 - 属性hasReleaseList @0
 - 属性hasProductPlanList @0
 - 属性releaseListContains @0
 - 属性productPlanListContains @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->saveSession4RoadmapTest()) && p('hasReleaseList') && e('0'); // 测试步骤1:调用saveSession4Roadmap方法,验证releaseList session值
r($productTest->saveSession4RoadmapTest()) && p('hasProductPlanList') && e('0'); // 测试步骤2:验证productPlanList session值
r($productTest->saveSession4RoadmapTest()) && p('releaseListContains') && e('0'); // 测试步骤3:验证hasReleaseList返回值
r($productTest->saveSession4RoadmapTest()) && p('productPlanListContains') && e('0'); // 测试步骤4:验证hasProductPlanList返回值
r($productTest->saveSession4RoadmapTest()) && p('hasReleaseList,hasProductPlanList,releaseListContains,productPlanListContains') && e('0,0,0,0'); // 测试步骤5:多次调用验证session值保持一致