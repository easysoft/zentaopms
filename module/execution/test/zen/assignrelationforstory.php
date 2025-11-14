#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignRelationForStory();
timeout=0
cid=16406

- 执行executionZenTest模块的assignRelationForStoryTest方法，参数是$execution1, $products1, 1, 'all', 'story', 0, 'id_desc', $pager 
 - 属性title @执行1-需求列表
 - 属性storyType @story
 - 属性orderBy @id_desc
- 执行executionZenTest模块的assignRelationForStoryTest方法，参数是$execution1, $products1, 0, 'all', 'story', 0, 'id_desc', $pager 第product条的id属性 @1
- 执行executionZenTest模块的assignRelationForStoryTest方法，参数是$execution1, $products1, 1, 'all', 'story', 0, 'id_desc', $pager 属性multiBranch @1
- 执行executionZenTest模块的assignRelationForStoryTest方法，参数是$execution2, $products2, 2, 'byproduct', 'requirement', 2, 'pri_desc', $pager 
 - 属性storyType @requirement
 - 属性type @byproduct
 - 属性param @2
- 执行executionZenTest模块的assignRelationForStoryTest方法，参数是$execution1, $products1, 1, 'bymodule', 'story', 1, 'stage_asc', $pager 
 - 属性orderBy @stage_asc
 - 属性param @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

su('admin');

$executionZenTest = new executionZenTest();

// 准备测试数据
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->name = '执行1';
$execution1->project = 1;

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->name = '执行2';
$execution2->project = 2;

$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品1';
$product1->type = 'normal';

$product2 = new stdClass();
$product2->id = 2;
$product2->name = '产品2';
$product2->type = 'branch';

$products1 = array(1 => $product1, 2 => $product2);
$products2 = array(2 => $product2);

$pager = new stdClass();
$pager->recPerPage = 20;

r($executionZenTest->assignRelationForStoryTest($execution1, $products1, 1, 'all', 'story', 0, 'id_desc', $pager)) && p('title,storyType,orderBy') && e('执行1-需求列表,story,id_desc');
r($executionZenTest->assignRelationForStoryTest($execution1, $products1, 0, 'all', 'story', 0, 'id_desc', $pager)) && p('product:id') && e('1');
r($executionZenTest->assignRelationForStoryTest($execution1, $products1, 1, 'all', 'story', 0, 'id_desc', $pager)) && p('multiBranch') && e('1');
r($executionZenTest->assignRelationForStoryTest($execution2, $products2, 2, 'byproduct', 'requirement', 2, 'pri_desc', $pager)) && p('storyType,type,param') && e('requirement,byproduct,2');
r($executionZenTest->assignRelationForStoryTest($execution1, $products1, 1, 'bymodule', 'story', 1, 'stage_asc', $pager)) && p('orderBy,param') && e('stage_asc,1');