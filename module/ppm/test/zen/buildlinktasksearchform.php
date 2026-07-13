#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::buildLinkTaskSearchForm();
timeout=0
cid=0

- 执行$searchForm属性module @ppmTask
- 执行$searchForm属性queryID @7
- 执行$searchForm['params']['execution']['values'] @2
- 执行$searchForm['fields']['module']) ? 1 : 0 @0
- 执行$searchForm['actionURL'], 'linkTask') !== false ? 1 : 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->rawModule = 'ppm';
$app->rawMethod = 'view';
$app->setMethodName('view');

su('admin');

$ppmZen     = new ppmZenTest();
$searchForm = $ppmZen->buildLinkTaskSearchFormTest(8101, 6101, 'id_desc', 7, array(1 => '执行A', 2 => '执行B', 0 => ''));

r($searchForm) && p('module') && e('ppmTask');
r($searchForm) && p('queryID') && e('7');
r(count($searchForm['params']['execution']['values'])) && p() && e('2');
r(isset($searchForm['fields']['module']) ? 1 : 0) && p() && e('0');
r(strpos($searchForm['actionURL'], 'linkTask') !== false ? 1 : 0) && p() && e('1');