#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerPri();
timeout=0
cid=18520

- 按照优先级分组，获取分组后的需求数量 @4
- 按照优先级分组，获取各个优先级的需求数量，查看优先级1下的数据
 - 第1条的name属性 @1
 - 第1条的value属性 @5
- 按照优先级分组，获取各个优先级的需求数量，查看优先级4下的数据
 - 第4条的name属性 @4
 - 第4条的value属性 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$story = zenData('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerPri();

r(count($data)) && p()                && e('4');   // 按照优先级分组，获取分组后的需求数量
r($data)        && p('1:name,value')  && e('1,5'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级1下的数据
r($data)        && p('4:name,value')  && e('4,4'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级4下的数据