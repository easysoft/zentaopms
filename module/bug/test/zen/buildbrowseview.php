#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBrowseView();
timeout=0
cid=0

- 步骤1：正常参数构建浏览视图
 - 属性bugCount @3
 - 属性productType @normal
 - 属性branchValid @1
 - 属性browseTypeValid @1
 - 属性pagerValid @1
- 步骤2：空bug列表构建浏览视图
 - 属性bugCount @0
 - 属性productType @normal
 - 属性moduleIDValid @1
 - 属性orderByValid @1
- 步骤3：不同产品类型构建浏览视图
 - 属性bugCount @3
 - 属性productType @branch
 - 属性branchValid @1
 - 属性browseTypeValid @1
 - 属性executionCount @2
- 步骤4：包含story和task的bugs构建视图
 - 属性bugCount @3
 - 属性storyCount @0
 - 属性taskCount @2
 - 属性orderByValid @1
- 步骤5：不同浏览类型构建视图
 - 属性paramValid @1
 - 属性orderByValid @1
 - 属性storyCount @2
 - 属性executionCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('Bug Title{1-10}');
$bug->product->range('1{5},2{5}');
$bug->story->range('1{3},2{2},0{5}');
$bug->task->range('1{2},2{1},0{7}');
$bug->toTask->range('0{8},3{1},4{1}');
$bug->openedBy->range('admin{5},user1{3},user2{2}');
$bug->status->range('active{6},resolved{2},closed{2}');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product{1-3}');
$product->type->range('normal{1},branch{1},platform{1}');
$product->gen(3);

$story = zenData('story');
$story->id->range('1-3');
$story->title->range('Story{1-3}');
$story->gen(3);

$task = zenData('task');
$task->id->range('1-5');
$task->name->range('Task{1-5}');
$task->gen(5);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('Admin,User1,User2,User3,User4,User5,User6,User7,User8,User9');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 创建分页对象
$pager = new stdclass();
$pager->recTotal = 0;
$pager->recPerPage = 20;
$pager->pageID = 1;

// 创建产品对象
$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'Product1';
$product1->type = 'normal';

$product2 = new stdclass();
$product2->id = 2;
$product2->name = 'Product2';
$product2->type = 'branch';

// 创建bug对象数组
$bugs1 = array();
for($i = 1; $i <= 3; $i++) {
    $bug = new stdclass();
    $bug->id = $i;
    $bug->title = "Bug Title{$i}";
    $bug->story = $i <= 2 ? $i : 0;
    $bug->task = $i == 1 ? 1 : 0;
    $bug->toTask = 0;
    $bugs1[$i] = $bug;
}

$bugs2 = array();
for($i = 4; $i <= 6; $i++) {
    $bug = new stdclass();
    $bug->id = $i;
    $bug->title = "Bug Title{$i}";
    $bug->story = 0;
    $bug->task = $i == 5 ? 2 : 0;
    $bug->toTask = $i == 6 ? 3 : 0;
    $bugs2[$i] = $bug;
}

// 5. 强制要求：必须包含至少5个测试步骤
r($bugTest->buildBrowseViewTest($bugs1, $product1, 'all', 'all', 0, array(), 0, 'id_desc', $pager)) && p('bugCount,productType,branchValid,browseTypeValid,pagerValid') && e('3,normal,1,1,1'); // 步骤1：正常参数构建浏览视图
r($bugTest->buildBrowseViewTest(array(), $product1, 'all', 'unclosed', 1, array(), 0, 'id_asc', $pager)) && p('bugCount,productType,moduleIDValid,orderByValid') && e('0,normal,1,1'); // 步骤2：空bug列表构建浏览视图
r($bugTest->buildBrowseViewTest($bugs1, $product2, '1', 'bymodule', 2, array(1, 2), 5, 'title_desc', $pager)) && p('bugCount,productType,branchValid,browseTypeValid,executionCount') && e('3,branch,1,1,2'); // 步骤3：不同产品类型构建浏览视图
r($bugTest->buildBrowseViewTest($bugs2, $product1, 'all', 'assignedtome', 0, array(), 0, 'status_asc', $pager)) && p('bugCount,storyCount,taskCount,orderByValid') && e('3,0,2,1'); // 步骤4：包含story和task的bugs构建视图
r($bugTest->buildBrowseViewTest($bugs1, $product1, '0', 'openedbyme', 3, array(5, 6, 7), 10, 'pri_desc', $pager)) && p('paramValid,orderByValid,storyCount,executionCount') && e('1,1,2,3'); // 步骤5：不同浏览类型构建视图