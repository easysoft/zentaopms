#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerOpenedBy();
cid=0

- 按照创建人分组，获取分组后的需求数量 @4
- 按照创建人分组，获取各个创建人的需求数量，查看用户test3下的数据
 - 第user2条的name属性 @用户2
 - 第user2条的value属性 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable("user")->gen(5);
$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerOpenedBy();

r(count($data)) && p()                   && e('4');       // 按照创建人分组，获取分组后的需求数量
r($data)        && p('user2:name,value') && e('用户2,5'); // 按照创建人分组，获取各个创建人的需求数量，查看用户test3下的数据
