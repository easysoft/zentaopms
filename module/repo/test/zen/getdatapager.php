#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getDataPager();
timeout=0
cid=18138

- 执行repoZenTest模块的getDataPagerTest方法，参数是$testData, $pager  @3
- 执行repoZenTest模块的getDataPagerTest方法，参数是$emptyData, $pager  @0
- 执行repoZenTest模块的getDataPagerTest方法，参数是$testData, $pager 第0条的id属性 @1
- 执行repoZenTest模块的getDataPagerTest方法，参数是$testData, $pager  @1
- 执行repoZenTest模块的getDataPagerTest方法，参数是$testData, $pager  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

$repoZenTest = new repoZenTest();

// 模拟分页器对象
$pager = new stdClass();
$pager->recPerPage = 3;
$pager->pageID = 1;

// 模拟测试数据
$testData = array(
    array('id' => 1, 'name' => 'item1'),
    array('id' => 2, 'name' => 'item2'),
    array('id' => 3, 'name' => 'item3'),
    array('id' => 4, 'name' => 'item4'),
    array('id' => 5, 'name' => 'item5'),
    array('id' => 6, 'name' => 'item6'),
    array('id' => 7, 'name' => 'item7')
);

// 测试第一页数据，期望3条数据
$pager->pageID = 1;
r(count($repoZenTest->getDataPagerTest($testData, $pager))) && p() && e('3');

// 测试空数据，期望返回空数组
$emptyData = array();
$pager->pageID = 1;
r(count($repoZenTest->getDataPagerTest($emptyData, $pager))) && p() && e('0');

// 测试第一页第一条数据的ID
$pager->pageID = 1;
r($repoZenTest->getDataPagerTest($testData, $pager)) && p('0:id') && e('1');

// 测试最后一页数据，期望1条数据
$pager->pageID = 3;
r(count($repoZenTest->getDataPagerTest($testData, $pager))) && p() && e('1');

// 测试超出页数范围，期望返回空数组
$pager->pageID = 5;
r(count($repoZenTest->getDataPagerTest($testData, $pager))) && p() && e('0');