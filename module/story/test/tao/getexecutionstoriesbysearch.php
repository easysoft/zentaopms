#!/usr/bin/env php
<?php

/**

title=测试 storyTest->getExecutionStoriesBySearch();
cid=18635

- 不传入数据。 @0
- 只传入产品。 @0
- 传入执行 ID。 @9
- 传入执行 ID，传入产品 ID。 @9
- 传入排除需求号。 @7
- 传入分页。 @5
- 只传入保存查询条件 ID. @0
- 传入保存查询条件 ID，传入产品 ID。 @0
- 传入执行 ID，传入保存查询条件 ID。 @1
- 传入执行 ID，传入保存查询条件 ID，传入产品 ID。 @1
- 搜索条件中有 result = pass 数据，传入执行 ID，不传入产品 ID。 @0
- 搜索条件中有 result = pass 数据，传入执行 ID，传入产品 ID。 @0
- 搜索条件中有 result = revert 数据，传入执行 ID，不传入产品 ID。 @0
- 搜索条件中有 result = revert 数据，传入执行 ID，传入产品 ID。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->gen(10);

$projectstory = zenData('projectstory');
$projectstory->project->range('11');
$projectstory->product->range('1');
$projectstory->story->range('1-18');
$projectstory->gen(18);

$story = zenData('story');
$story->product->range(1);
$story->version->range(1);
$story->gen(20);

$storyreview = zenData('storyreview');
$storyreview->result = 'revert{10},pass{10}';
$storyreview->gen(20);

$action = zenData('action');
$action->product->range('`,1,`');
$action->action->range('reviewed');
$action->objectType->range('story');
$action->execution->range('0');
$action->objectID->range('1-3');
$action->extra->range('Revert');
$action->gen(10);

$userquery = zenData('userquery');
$userquery->sql->range('(1 = 1 AND `id` = 2)');
$userquery->form->range('``');
$userquery->gen(1);

$storyTest = new storyTaoTest();

$executionID = array(0, 11);
$queryID     = array(0, 1);
$productID   = array(0, 1);

//getExecutionStoriesBySearchTest(int $executionID, int $queryID, int $productID, string $orderBy, string $storyType, array $excludeStories, object|null $pager = null): array
$_SESSION['executionStoryQuery'] = '1 = 1';
r($storyTest->getExecutionStoriesBySearchTest($executionID[0], $queryID[0], $productID[0])) && p() && e('0'); //不传入数据。
r($storyTest->getExecutionStoriesBySearchTest($executionID[0], $queryID[0], $productID[1])) && p() && e('0'); //只传入产品。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[0])) && p() && e('9'); //传入执行 ID。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[1])) && p() && e('9'); //传入执行 ID，传入产品 ID。

$storyTest->objectModel->app->loadClass('pager', $static = true);
$storyTest->objectModel->app->rawModule = 'product';
$storyTest->objectModel->app->rawMethod = 'track';
$pager          = new pager(0, 5, 1);
$excludeStories = array(2, 4);
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[1], $excludeStories)) && p() && e('7'); //传入排除需求号。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[1], array(), $pager)) && p() && e('5'); //传入分页。

r($storyTest->getExecutionStoriesBySearchTest($executionID[0], $queryID[1], $productID[0])) && p() && e('0'); //只传入保存查询条件 ID.
r($storyTest->getExecutionStoriesBySearchTest($executionID[0], $queryID[1], $productID[1])) && p() && e('0'); //传入保存查询条件 ID，传入产品 ID。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[1], $productID[0])) && p() && e('1'); //传入执行 ID，传入保存查询条件 ID。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[1], $productID[1])) && p() && e('1'); //传入执行 ID，传入保存查询条件 ID，传入产品 ID。

$_SESSION['executionStoryQuery'] = "`result` = 'pass'";
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[0])) && p() && e('0'); //搜索条件中有 result = pass 数据，传入执行 ID，不传入产品 ID。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[1])) && p() && e('0'); //搜索条件中有 result = pass 数据，传入执行 ID，传入产品 ID。

$_SESSION['executionStoryQuery'] = "`result` = 'revert'";
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[0])) && p() && e('0'); //搜索条件中有 result = revert 数据，传入执行 ID，不传入产品 ID。
r($storyTest->getExecutionStoriesBySearchTest($executionID[1], $queryID[0], $productID[1])) && p() && e('1'); //搜索条件中有 result = revert 数据，传入执行 ID，传入产品 ID。
