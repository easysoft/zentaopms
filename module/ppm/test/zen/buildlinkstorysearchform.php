#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行$searchForm属性queryID @9
- 执行$searchForm属性style @simple
- 执行$searchForm['params']['status']['values']['closed']) ? 1 : 0 @0
- 执行$searchForm['fields']['product']) ? 1 : 0 @0
- 执行$searchForm['fields']['branch']) ? 1 : 0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->rawModule = 'ppm';
$app->rawMethod = 'view';
$app->setMethodName('view');

su('admin');

$ppmZen    = new ppmZenTest();
$searchForm = $ppmZen->buildLinkStorySearchFormTest(8101, 6101, 'id_desc', 9);

r($searchForm) && p('queryID') && e('9');
r($searchForm) && p('style') && e('simple');
r(isset($searchForm['params']['status']['values']['closed']) ? 1 : 0) && p() && e('0');
r(isset($searchForm['fields']['product']) ? 1 : 0) && p() && e('0');
r(isset($searchForm['fields']['branch']) ? 1 : 0) && p() && e('0');