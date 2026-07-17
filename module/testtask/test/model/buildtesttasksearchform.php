#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试testtaskModel->buildTesttaskSearchForm();
timeout=0
cid=16273

- 缓存查询参数，查询参数中 queryID 为空。 @0
- 缓存查询参数，查询参数中 actionURL 为空。 @0
- 缓存查询参数，查询参数中有 product 字段。 @1
- 缓存查询参数，查询参数中有 pri 字段。 @1
- module 为 projectTesttask,缓存查询参数，打印 module 的值。属性module @testtask

*/

global $tester;
$testtask   = $tester->loadModel('testtask');
$productID = 1;
$queryID   = 1;
$actionURL = '';
$module    = 'testtask';

$searchConfig = $testtask->buildTesttaskSearchForm($productID, $queryID, $actionURL, true);
r(isset($searchConfig['queryID']))           && p()         && e(0); // 缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))         && p()         && e(0); // 缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['product'])) && p()         && e(1); // 缓存查询参数，查询参数中有 product 字段。
r(isset($searchConfig['fields']['pri']))     && p()         && e(1); // 缓存查询参数，查询参数中有 pri 字段。
r($searchConfig)                             && p('module') && e('testtask'); // module 为 projectTesttask,缓存查询参数，打印 module 的值。