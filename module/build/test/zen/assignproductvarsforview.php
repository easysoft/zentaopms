#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignProductVarsForView();
timeout=0
cid=0

- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build1, 'story', 'id_desc', $storyPager 属性branchName @主干
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build2, 'story', 'title_asc', $storyPager 属性branchName @开发分支
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build3, 'bug', 'id_asc', $storyPager 属性branchName @主干
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build4, 'story', 'pri_desc', $storyPager 属性branchName @主干
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build5, 'story', 'id_asc', $storyPager 
 - 属性branchName @开发分支
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build6, 'story', 'status_asc', $storyPager 属性storyCount @4
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build7, 'story', 'id_desc', $storyPager 属性branchName @主干
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build8, 'story', 'title_asc', $storyPager 
 - 属性branchName @主干

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 准备测试数据
$table = zenData('build');
$table->id->range('1-10');
$table->product->range('1{4},2{3},3{3}');
$table->branch->range('0{5},1{3},2{2}');
$table->name->range('构建版本{10}');
$table->stories->range('"1,2,3"{3},"4,5,6"{3},"7,8"{2},"1,3,5,7"{2}');
$table->gen(10);

$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品A,产品B,产品C,多分支产品,普通产品');
$table->type->range('normal{2},branch{2},platform{1}');
$table->gen(5);

$table = zenData('branch');
$table->id->range('1-5');
$table->product->range('2{3},4{2}');
$table->name->range('开发分支,测试分支,发布分支,热修复分支,主分支');
$table->gen(5);

$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1{4},2{3},3{3}');
$table->title->range('用户登录功能{2},数据统计功能{2},报表导出功能{2},权限管理功能{2},系统配置功能{2}');
$table->status->range('active');
$table->gen(10);

su('admin');

$buildTest = new buildTest();

// 构建分页对象
$storyPager = new stdclass();
$storyPager->pageID = 1;
$storyPager->recPerPage = 20;

// 测试步骤1：测试正常产品类型的分支名称处理
$build1 = new stdclass();
$build1->product = 1;
$build1->productType = 'normal';
$build1->branch = '0';
$build1->allStories = '1,2,3';
r($buildTest->assignProductVarsForViewTest($build1, 'story', 'id_desc', $storyPager)) && p('branchName') && e('主干');

// 测试步骤2：测试多分支产品类型的分支名称处理
$build2 = new stdclass();
$build2->product = 2;
$build2->productType = 'branch';
$build2->branch = '1';
$build2->allStories = '4,5,6';
r($buildTest->assignProductVarsForViewTest($build2, 'story', 'title_asc', $storyPager)) && p('branchName') && e('开发分支');

// 测试步骤3：测试平台产品类型的分支名称处理
$build3 = new stdclass();
$build3->product = 4;
$build3->productType = 'platform';
$build3->branch = '0';
$build3->allStories = '7,8';
r($buildTest->assignProductVarsForViewTest($build3, 'bug', 'id_asc', $storyPager)) && p('branchName') && e('主干');

// 测试步骤4：测试空分支情况的处理
$build4 = new stdclass();
$build4->product = 3;
$build4->productType = 'branch';
$build4->branch = '';
$build4->allStories = '';
r($buildTest->assignProductVarsForViewTest($build4, 'story', 'pri_desc', $storyPager)) && p('branchName') && e('主干');

// 测试步骤5：测试多个分支的处理
$build5 = new stdclass();
$build5->product = 2;
$build5->productType = 'branch';
$build5->branch = '1,2';
$build5->allStories = '4,5';
r($buildTest->assignProductVarsForViewTest($build5, 'story', 'id_asc', $storyPager)) && p('branchName') && e('开发分支,测试分支');

// 测试步骤6：测试故事列表的正确获取
$build6 = new stdclass();
$build6->product = 1;
$build6->productType = 'normal';
$build6->branch = '0';
$build6->allStories = '1,3,5,7';
r($buildTest->assignProductVarsForViewTest($build6, 'story', 'status_asc', $storyPager)) && p('storyCount') && e('4');

// 测试步骤7：测试主干分支标识的处理
$build7 = new stdclass();
$build7->product = 2;
$build7->productType = 'branch';
$build7->branch = '0';
$build7->allStories = '4';
r($buildTest->assignProductVarsForViewTest($build7, 'story', 'id_desc', $storyPager)) && p('branchName') && e('主干');

// 测试步骤8：测试混合分支的处理（包含主干和普通分支）
$build8 = new stdclass();
$build8->product = 2;
$build8->productType = 'branch';
$build8->branch = '0,1';
$build8->allStories = '4,5,6';
r($buildTest->assignProductVarsForViewTest($build8, 'story', 'title_asc', $storyPager)) && p('branchName') && e('主干,开发分支');