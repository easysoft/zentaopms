#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignProductVarsForView();
timeout=0
cid=0

- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build1, '', '', $pager 属性branchName @主干
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build2, 'story', '', $pager 属性branchName @V1
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build3, 'story', '', $pager 属性branchName @V2
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build1, '', '', $pager 属性storyCount @3
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build4, '', '', $pager 属性storyCount @0
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build1, 'story', '', $pager 属性hasStoryPager @1
- 执行buildTest模块的assignProductVarsForViewTest方法，参数是$build5, '', '', $pager 属性branchName @主干

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1-3');
$story->branch->range('0{10},1{5},2{5}');
$story->title->range('Story 1-20')->prefix('Story ');
$story->status->range('active{10},closed{5},draft{5}');
$story->type->range('story');
$story->gen(20);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product 1-5')->prefix('Product ');
$product->type->range('normal{2},branch{2},platform{1}');
$product->gen(5);

$build = zenData('build');
$build->id->range('1-10');
$build->product->range('1-3');
$build->branch->range('0{5},1{3},2{2}');
$build->name->range('Build 1-10')->prefix('Build ');
$build->gen(10);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('3{3},4{2}');
$branch->name->range('V1,V2,V3,V4,V5');
$branch->status->range('active');
$branch->gen(5);

zenData('project')->gen(5);
zenData('user')->gen(5);

global $tester;
$tester->app->loadClass('pager', true);
$tester->app->rawModule = 'build';
$tester->app->rawMethod = 'view';

su('admin');

$buildTest = new buildZenTest();

$build1 = new stdclass();
$build1->id           = 1;
$build1->project      = 1;
$build1->product      = 1;
$build1->branch       = '0';
$build1->productType  = 'normal';
$build1->allStories   = '1,2,3';

$build2 = new stdclass();
$build2->id           = 2;
$build2->project      = 1;
$build2->product      = 3;
$build2->branch       = '1';
$build2->productType  = 'branch';
$build2->allStories   = '11,12,13';

$build3 = new stdclass();
$build3->id           = 3;
$build3->project      = 1;
$build3->product      = 3;
$build3->branch       = '2';
$build3->productType  = 'branch';
$build3->allStories   = '16,17';

$build6 = new stdclass();
$build6->id           = 6;
$build6->project      = 1;
$build6->product      = 3;
$build6->branch       = '1,2';
$build6->productType  = 'branch';
$build6->allStories   = '11,12,13,16,17';

$build4 = new stdclass();
$build4->id           = 4;
$build4->project      = 1;
$build4->product      = 1;
$build4->branch       = '0';
$build4->productType  = 'normal';
$build4->allStories   = '';

$build5 = new stdclass();
$build5->id           = 5;
$build5->project      = 1;
$build5->product      = 1;
$build5->branch       = '';
$build5->productType  = 'normal';
$build5->allStories   = '1,2';

$pager = new pager(0, 10, 1);

r($buildTest->assignProductVarsForViewTest($build1, '', '', $pager)) && p('branchName') && e('主干');
r($buildTest->assignProductVarsForViewTest($build2, 'story', '', $pager)) && p('branchName') && e('V1');
r($buildTest->assignProductVarsForViewTest($build3, 'story', '', $pager)) && p('branchName') && e('V2');
r($buildTest->assignProductVarsForViewTest($build1, '', '', $pager)) && p('storyCount') && e('3');
r($buildTest->assignProductVarsForViewTest($build4, '', '', $pager)) && p('storyCount') && e('0');
r($buildTest->assignProductVarsForViewTest($build1, 'story', '', $pager)) && p('hasStoryPager') && e('1');
r($buildTest->assignProductVarsForViewTest($build5, '', '', $pager)) && p('branchName') && e('主干');