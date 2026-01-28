#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getBySQL();
timeout=0
cid=18510

- 执行storyTest模块的getBySQLTest方法，参数是1, "1 = 1 AND `status` = 'active'", 'id_desc'  @2
- 执行storyTest模块的getBySQLTest方法，参数是'all', "1 = 1 AND `type` = 'story'", 'id_asc'  @15
- 执行storyTest模块的getBySQLTest方法，参数是2, "1 = 1", 'id'  @3
- 执行storyTest模块的getBySQLTest方法，参数是'all', "1 = 1", 'id', null, 'requirement'  @5
- 执行storyTest模块的getBySQLTest方法，参数是'all', "1 = 1 AND `status` = 'draft'", 'id'  @4
- 执行storyTest模块的getBySQLTest方法，参数是3, "1 = 1", 'id'  @3
- 执行storyTest模块的getBySQLTest方法，参数是999, "1 = 1", 'id'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('product')->gen(5);
zenData('productplan')->gen(10);

$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1-5');
$story->type->range('story{15},requirement{5}');
$story->status->range('active{8},draft{4},reviewing{3},changing{2},closed{3}');
$story->vision->range('rnd');
$story->version->range('1');
$story->gen(20);

su('admin');

$storyTest = new storyModelTest();

// 测试步骤1：正常查询指定产品的活跃需求
r($storyTest->getBySQLTest(1, "1 = 1 AND `status` = 'active'", 'id_desc')) && p() && e('2');

// 测试步骤2：查询所有产品的需求
r($storyTest->getBySQLTest('all', "1 = 1 AND `type` = 'story'", 'id_asc')) && p() && e('15');

// 测试步骤3：测试空SQL条件查询
r($storyTest->getBySQLTest(2, "1 = 1", 'id')) && p() && e('3');

// 测试步骤4：测试不同类型过滤(requirement类型)
r($storyTest->getBySQLTest('all', "1 = 1", 'id', null, 'requirement')) && p() && e('5');

// 测试步骤5：测试特定状态过滤
r($storyTest->getBySQLTest('all', "1 = 1 AND `status` = 'draft'", 'id')) && p() && e('4');

// 测试步骤6：测试单个产品的需求数量
r($storyTest->getBySQLTest(3, "1 = 1", 'id')) && p() && e('3');

// 测试步骤7：测试无效产品ID
r($storyTest->getBySQLTest(999, "1 = 1", 'id')) && p() && e('0');