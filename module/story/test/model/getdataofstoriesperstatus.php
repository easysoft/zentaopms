#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerStatus();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerStatus();

r(count($data)) && p()                    && e('4');        // 按照需求状态分组，获取分组后的需求数量
r($data)        && p('draft:name,value')  && e('草稿,5');   // 按照需求状态分组，获取各个需求状态的需求数量，查看draft下的数据
r($data)        && p('active:name,value') && e('激活,5');   // 按照需求状态分组，获取各个需求状态的需求数量，查看active下的数据
r($data)        && p('closed:name,value') && e('已关闭,5'); // 按照需求状态分组，获取各个需求状态的需求数量，查看closed下的数据
