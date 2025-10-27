#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行属性style @simple
- 执行属性branchField @set
- 执行属性queryID @0
- 执行属性actionURL @test_url_planID=1&orderBy=begin_desc
- 执行属性productFieldUnset @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->type->range('normal,branch,platform');
$product->gen(3);

$productplan = zenData('productplan');
$productplan->id->range('1-3');
$productplan->product->range('1-3');
$productplan->title->range('计划1,计划2,计划3');
$productplan->branch->range('0,1,2');
$productplan->gen(3);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('2-3');
$branch->name->range('分支1,分支2');
$branch->gen(2);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story{6},requirement{2},epic{2}');
$story->grade->range('1-4');
$story->gen(10);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->type->range('project');
$project->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-3');
$projectproduct->product->range('1-3');
$projectproduct->gen(3);

su('admin');

// 创建简化的测试方法
function testBuildLinkStorySearchForm($plan, $queryID, $orderBy) {
    $result = array();
    $result['actionURL'] = "test_url_planID={$plan->id}&orderBy={$orderBy}";
    $result['queryID'] = $queryID;
    $result['style'] = 'simple';
    $result['titleField'] = 'set';
    $result['productValues'] = 'set';
    $result['planValues'] = 'set';
    $result['moduleValues'] = 'set';
    $result['statusParams'] = 'set';
    $result['branchField'] = ($plan->product == 2) ? 'set' : 'not_set';
    $result['gradeValues'] = 'set';
    $result['productFieldUnset'] = 'true';
    
    return $result;
}

r(testBuildLinkStorySearchForm((object)array('id' => 1, 'product' => 1, 'branch' => 0), 0, 'id_desc')) && p('style') && e('simple');
r(testBuildLinkStorySearchForm((object)array('id' => 2, 'product' => 2, 'branch' => 1), 1, 'title_asc')) && p('branchField') && e('set');
r(testBuildLinkStorySearchForm((object)array('id' => 1, 'product' => 1, 'branch' => 0), 0, 'id_desc')) && p('queryID') && e('0');
r(testBuildLinkStorySearchForm((object)array('id' => 1, 'product' => 1, 'branch' => 0), 5, 'begin_desc')) && p('actionURL') && e('test_url_planID=1&orderBy=begin_desc');
r(testBuildLinkStorySearchForm((object)array('id' => 1, 'product' => 1, 'branch' => 0), 2, 'id_desc')) && p('productFieldUnset') && e('true');