#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$module = zdTable('action');
$module->product->range('`,1,`');
$module->action->range('reviewed');
$module->objectType->range('story');
$module->execution->range('0');
$module->objectID->range('1-3');
$module->extra->range('Revert');
$module->gen(10);

su('admin');

/**

title=测试 storyModel->replaceRevertQuery();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->replaceRevertQuery('', 0)) && p() && e('0'); //不传入数据。
r($storyModel->replaceRevertQuery('', 1)) && p() && e('0'); //传入产品参数，不传入查询语句。

$query = "AND `result` = 'pass'";
r($storyModel->replaceRevertQuery($query, 0) == $query) && p() && e('1'); //传入不符合条件的查询语句，不传入产品参数。
r($storyModel->replaceRevertQuery($query, 1) == $query) && p() && e('1'); //传入不符合条件的查询语句，传入产品参数。

$query = "AND `result` = 'revert'";
r($storyModel->replaceRevertQuery($query, 0) == "AND 1 = 1 AND `id` IN ('')")          && p() && e('0'); //传入符合条件查询语句，不传入产品参数。
r($storyModel->replaceRevertQuery($query, 1) == "AND 1 = 1 AND `id` IN ('3','2','1')") && p() && e('1'); //传入符合条件查询语句，传入有数据的产品参数。
r($storyModel->replaceRevertQuery($query, 2) == "AND 1 = 1 AND `id` IN ('')")          && p() && e('0'); //传入符合条件查询语句，传入无数据的产品参数。
