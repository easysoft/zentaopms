#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildTrackLanes();
timeout=0
cid=0

- 传入空参数。 @0
- 检查泳道数。 @4
- 检查第一个泳道的name参数。 @lane_1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->loadModel('story');

$leafNodes = array();
foreach(array(1,2,3,4) as $storyID)
{
    $story = new stdclass();
    $story->id = $storyID;
    $leafNodes[$storyID] = $story;
}
r(count($tester->story->buildTrackLanes(array()))) && p() && e('0');  //传入空参数。

$lanes = $tester->story->buildTrackLanes($leafNodes);
r(count($lanes)) && p() && e('4');          //检查泳道数。
r($lanes[0]['name']) && p() && e('lane_1'); //检查第一个泳道的name参数。
