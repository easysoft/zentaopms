#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerStage();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerStage();

r(count($data)) && p()                      && e('10');       // 按照需求阶段分组，获取分组后的需求数量
r($data)        && p('wait:name,value')     && e('未开始,1'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看wait下的数据
r($data)        && p('planned:name,value')  && e('已计划,1'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看planned下的数据
r($data)        && p('released:name,value') && e('已发布,1'); // 按照需求阶段分组，获取各个需求阶段的需求数量，查看released下的数据
