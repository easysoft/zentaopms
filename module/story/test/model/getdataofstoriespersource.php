#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerSource();
timeout=0
cid=18522

- 按照需求来源分组，获取分组后的需求数量 @14
- 按照需求来源分组，获取各个需求来源的需求数量，查看support下的数据
 - 第support条的name属性 @技术支持
 - 第support条的value属性 @1
- 按照需求来源分组，获取各个需求来源的需求数量，查看market下的数据
 - 第market条的name属性 @市场
 - 第market条的value属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$story = zenData('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerSource();

r(count($data)) && p()                     && e('14');          // 按照需求来源分组，获取分组后的需求数量
r($data)        && p('support:name,value') && e('技术支持,1'); // 按照需求来源分组，获取各个需求来源的需求数量，查看support下的数据
r($data)        && p('market:name,value')  && e('市场,2');     // 按照需求来源分组，获取各个需求来源的需求数量，查看market下的数据