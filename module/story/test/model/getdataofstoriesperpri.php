#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerPri();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerPri();

r(count($data)) && p()                && e('4');   // 按照优先级分组，获取分组后的需求数量
r($data)        && p('1:name,value')  && e('1,5'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级1下的数据
r($data)        && p('4:name,value')  && e('4,4'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级4下的数据
