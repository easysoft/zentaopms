#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试myModel->buildTesttaskSearchForm();
timeout=0
cid=16273

- 缓存查询参数，查询参数中 queryID 为空。 @0
- 缓存查询参数，查询参数中 actionURL 为空。 @0
- 缓存查询参数，查询参数中有 product 字段。 @1
- 缓存查询参数，查询参数中有 pri 字段。 @1
- module 为 myTesttask,缓存查询参数，打印 module 的值。属性module @myTesttask

*/

global $tester;
$my   = $tester->loadModel('my');
$queryID   = 1;
$products  = array(0 => '');
$actionURL = '';
$module    = 'myTesttask';

$searchConfig = $my->buildTesttaskSearchForm($queryID, $actionURL, true);
r(isset($searchConfig['queryID']))                && p()         && e(0);       // 缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p()         && e(0);       // 缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['product']))      && p()         && e(1);       // 缓存查询参数，查询参数中有 product 字段。
r(isset($searchConfig['fields']['pri']))          && p()         && e(1);       // 缓存查询参数，查询参数中有 pri 字段。
r($searchConfig)                                  && p('module') && e('myTesttask');  // module 为 myTesttask,缓存查询参数，打印 module 的值。