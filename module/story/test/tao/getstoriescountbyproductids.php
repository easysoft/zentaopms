#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getStoriesCountByProductIDs();
timeout=0
cid=18647

- 执行storyTest模块的getStoriesCountByProductIDsTest方法，参数是array  @2
- 执行storyTest模块的getStoriesCountByProductIDsTest方法，参数是array  @1
- 执行storyTest模块的getStoriesCountByProductIDsTest方法，参数是array  @1
- 执行storyTest模块的getStoriesCountByProductIDsTest方法，参数是array  @0
- 执行storyTest模块的getStoriesCountByProductIDsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$table = zenData('story');
$table->id->range('1-20');
$table->product->range('1{5},2{5},3{5},4{5}');
$table->type->range('requirement{10},story{5},epic{5}');
$table->status->range('active{8},closed{4},draft{4},reviewing{2},developing{2}');
$table->deleted->range('0{20}');
$table->gen(20);

su('admin');

$storyTest = new storyTaoTest();

r(count($storyTest->getStoriesCountByProductIDsTest(array(1, 2), 'requirement'))) && p() && e('2');
r(count($storyTest->getStoriesCountByProductIDsTest(array(1), 'requirement'))) && p() && e('1');
r(count($storyTest->getStoriesCountByProductIDsTest(array(3, 4), 'story'))) && p() && e('1');
r(count($storyTest->getStoriesCountByProductIDsTest(array(999), 'requirement'))) && p() && e('0');
r(count($storyTest->getStoriesCountByProductIDsTest(array(), 'requirement'))) && p() && e('0');