#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zdTable('story');
$story->estimate->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerEstimate();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerEstimate();

r(count($data)) && p()               && e('4');    // 按照需求预计工时分组，获取分组后的需求数量
r($data)        && p('0:name,value') && e('4,4');  // 按照需求预计工时分组，获取各个工时的需求数量，查看工时为19的数据
r($data)        && p('1:name,value') && e('1,5');  // 按照需求预计工时分组，获取各个工时的需求数量，查看工时为20的数据
