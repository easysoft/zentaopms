#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerProduct();
cid=0

- 按照产品分组，获取分组后的需求数量 @5
- 按照产品分组，获取各个产品下的需求数量，查看产品1下的数据
 - 第1条的name属性 @正常产品1
 - 第1条的value属性 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->gen(10);
$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerProduct();

r(count($data)) && p()                && e('5');          // 按照产品分组，获取分组后的需求数量
r($data)        && p('1:name,value') && e('正常产品1,4'); // 按照产品分组，获取各个产品下的需求数量，查看产品1下的数据
