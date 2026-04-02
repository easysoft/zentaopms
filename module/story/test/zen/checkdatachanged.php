#!/usr/bin/env php
<?php
/**

title=测试 storyZen::checkDataChanged();
timeout=0
cid=18665

- 测试添加备注触发变更 @1
- 测试删除附件触发变更 @1
- 测试修改名字触发变更 @1
- 测试修改文档版本触发变更 @1
- 测试没有修改不触发变更 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
zenData('story')->gen(10);
zenData('user')->gen(10);
su('admin');

$storyID = 1;
$emptyData = new stdclass();
$emptyData->reviewer = array();

$storyData = clone $emptyData;
$storyData->title = '变更后的需求';

$postData[] = array('comment' => '备注1');
$postData[] = array('deleteFiles' => 1);
$postData[] = array('docVersions' => array(1 => 1));

$storyTest = new storyZenTest();
r($storyTest->checkDataChangedTest($storyID, $emptyData, $postData[0])) && p() && e('1'); // 测试添加备注触发变更
r($storyTest->checkDataChangedTest($storyID, $emptyData, $postData[1])) && p() && e('1'); // 测试删除附件触发变更
r($storyTest->checkDataChangedTest($storyID, $storyData, array()))      && p() && e('1'); // 测试修改名字触发变更
r($storyTest->checkDataChangedTest($storyID, $emptyData, $postData[2])) && p() && e('1'); // 测试修改文档版本触发变更
r($storyTest->checkDataChangedTest($storyID, $emptyData, array()))      && p() && e('0'); // 测试没有修改不触发变更
