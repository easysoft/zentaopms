#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerChange();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerChange();

r(count($data)) && p()               && e('4');   // 按照需求版本号分组，获取分组后的需求数量
r($data)        && p('1:name,value') && e('0,5'); // 按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据
r($data)        && p('2:name,value') && e('1,5'); // 按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据
