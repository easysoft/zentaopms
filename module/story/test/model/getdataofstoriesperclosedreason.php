#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zdTable('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerClosedReason();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerClosedReason();

r(count($data)) && p()                       && e('7');          // 按照需求关闭原因分组，获取分组后的需求数量
r($data)        && p('willnotdo:name,value') && e('不做,3');     // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看willnotdo的数据
r($data)        && p('bydesign:name,value')  && e('设计如此,2'); // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看bydesign的数据
r($data)        && p('cancel:name,value')    && e('已取消,2');   // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看cancel的数据
