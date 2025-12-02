#!/usr/bin/env php
<?php
/**

title=测试 storyZen::buildStoriesForBatchCreate();
timeout=0
cid=18667

- 执行storyTest模块的buildStoriesForBatchCreateTest方法，参数是1, 'story'  @0
- 执行$result1
 - 第0条的title属性 @测试需求1
 - 第0条的pri属性 @1
- 执行$result2
 - 第0条的title属性 @测试需求2
 - 第0条的pri属性 @2
- 执行$result3
 - 第0条的title属性 @测试需求3
 - 第0条的pri属性 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->vision->range('rnd');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：空POST数据情况，返回空数组
$_POST = array('title' => array(''));
r($storyTest->buildStoriesForBatchCreateTest(1, 'story')) && p() && e('0');

// 步骤2：基本数据，也返回空数组
$_POST = array();
$_POST['title']    = array('测试需求1');
$_POST['pri']      = array('1');
$_POST['reviewer'] = array('admin');
$result1 = $storyTest->buildStoriesForBatchCreateTest(1, 'story');
r($result1) && p('0:title,pri') && e('测试需求1,1');

// 步骤3：检查requirement类型
$_POST['title'] = array('测试需求2');
$_POST['pri']   = array('2');
$result2 = $storyTest->buildStoriesForBatchCreateTest(1, 'requirement');
r($result2) && p('0:title,pri') && e('测试需求2,2');

// 步骤4：检查epic类型
$_POST['title'] = array('测试需求3');
$_POST['pri']   = array('3');
$result3 = $storyTest->buildStoriesForBatchCreateTest(1, 'epic');
r($result3) && p('0:title,pri') && e('测试需求3,3');
