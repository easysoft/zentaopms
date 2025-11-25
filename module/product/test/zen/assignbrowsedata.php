#!/usr/bin/env php
<?php

/**

title=测试 productZen::assignBrowseData();
timeout=0
cid=17561

- 测试步骤1: 空需求列表
 - 属性productID @1
 - 属性storyType @story
- 测试步骤2: 普通产品story类型
 - 属性productID @1
 - 属性storyType @story
- 测试步骤3: requirement类型
 - 属性productID @1
 - 属性storyType @requirement
- 测试步骤4: 项目需求场景属性isProjectStory @1
- 测试步骤5: 带分支产品属性branch @1
- 测试步骤6: from为doc
 - 属性productID @1
 - 属性storyType @story
- 测试步骤7: browseType为bysearch属性browseType @bysearch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);
zenData('project')->gen(10);
zenData('story')->gen(20);
zenData('user')->gen(10);

su('admin');

$productTest = new productZenTest();

/* 准备测试数据 */
$emptyStories = array();

$story1 = new stdclass();
$story1->id = 1;
$story1->title = 'Test Story 1';
$story1->type = 'story';
$story1->status = 'active';

$story2 = new stdclass();
$story2->id = 2;
$story2->title = 'Test Story 2';
$story2->type = 'requirement';
$story2->status = 'active';

$stories = array(1 => $story1, 2 => $story2);

$product = new stdclass();
$product->id = 1;
$product->name = 'Test Product';
$product->type = 'normal';

$productWithBranch = new stdclass();
$productWithBranch->id = 2;
$productWithBranch->name = 'Test Product with Branch';
$productWithBranch->type = 'branch';

$project = new stdclass();
$project->id = 1;
$project->name = 'Test Project';

r($productTest->assignBrowseDataTest($emptyStories, 'all', 'story', false, $product, null, '', '', '')) && p('productID,storyType') && e('1,story'); // 测试步骤1: 空需求列表
r($productTest->assignBrowseDataTest($stories, 'unclosed', 'story', false, $product, null, '', '', '')) && p('productID,storyType') && e('1,story'); // 测试步骤2: 普通产品story类型
r($productTest->assignBrowseDataTest($stories, 'unclosed', 'requirement', false, $product, null, '', '', '')) && p('productID,storyType') && e('1,requirement'); // 测试步骤3: requirement类型
r($productTest->assignBrowseDataTest($stories, 'unclosed', 'story', true, $product, $project, '', '', '')) && p('isProjectStory') && e('1'); // 测试步骤4: 项目需求场景
r($productTest->assignBrowseDataTest($stories, 'unclosed', 'story', false, $productWithBranch, null, '1', '1', '')) && p('branch') && e('1'); // 测试步骤5: 带分支产品
r($productTest->assignBrowseDataTest($stories, 'bysearch', 'story', false, $product, null, '', '', 'doc')) && p('productID,storyType') && e('1,story'); // 测试步骤6: from为doc
r($productTest->assignBrowseDataTest($stories, 'bysearch', 'requirement', false, $product, null, '', '', '')) && p('browseType') && e('bysearch'); // 测试步骤7: browseType为bysearch