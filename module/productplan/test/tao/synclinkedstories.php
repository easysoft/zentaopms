#!/usr/bin/env php
<?php
/**

title=测试 productplanTao->syncLinkedStories()
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('planstory')->gen(0);
$productplan = new productPlan('admin');

r($productplan->syncLinkedStoriesTest(array()))               && p() && e('0'); // 需求为空
r(count($productplan->syncLinkedStoriesTest(array(1, 4, 7)))) && p() && e('3'); // 获取需求id是1,4,7
