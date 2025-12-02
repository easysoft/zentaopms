#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveBackUriSessionForDynamic();
timeout=0
cid=17607

- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasProductList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasProductPlanList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasReleaseList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasStoryList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasProjectList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasExecutionList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasTaskList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasBuildList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasBugList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasCaseList @0
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性hasTesttaskList @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->saveBackUriSessionForDynamicTest()) && p('hasProductList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasProductPlanList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasReleaseList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasStoryList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasProjectList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasExecutionList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasTaskList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasBuildList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasBugList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasCaseList') && e('0');
r($productTest->saveBackUriSessionForDynamicTest()) && p('hasTesttaskList') && e('0');