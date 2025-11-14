#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerClosedReason();
timeout=0
cid=18514

- 按照需求关闭原因分组，获取分组后的需求数量 @7
- 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看willnotdo的数据
 - 第willnotdo条的name属性 @不做
 - 第willnotdo条的value属性 @3
- 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看bydesign的数据
 - 第bydesign条的name属性 @设计如此
 - 第bydesign条的value属性 @2
- 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看cancel的数据
 - 第cancel条的name属性 @已取消
 - 第cancel条的value属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

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