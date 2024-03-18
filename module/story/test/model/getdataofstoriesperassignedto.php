#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerAssignedTo();
cid=0

- 按照需求指派人分组，获取分组后的需求数量 @4
- 按照需求指派人分组，获取各个指派人名下的需求数量，查看admin下的数据
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerAssignedTo();

r(count($data)) && p()                   && e('4');       // 按照需求指派人分组，获取分组后的需求数量
r($data)        && p('admin:name,value') && e('admin,5'); // 按照需求指派人分组，获取各个指派人名下的需求数量，查看admin下的数据
