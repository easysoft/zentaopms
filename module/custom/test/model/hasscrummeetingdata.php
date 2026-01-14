#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasScrumMeetingData();
timeout=0
cid=15912

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->model->range('scrum');
$projectTable->gen(5);

zenData('meeting')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasScrumMeetingDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无会议数据
r($customTester->hasScrumMeetingDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无会议数据
r($customTester->hasScrumMeetingDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无会议数据

$meetingTable = zenData('meeting');
$meetingTable->deleted->range('0');
$meetingTable->project->range('1-5');
$meetingTable->gen(5);
r($customTester->hasScrumMeetingDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有会议数据
r($customTester->hasScrumMeetingDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有会议数据
r($customTester->hasScrumMeetingDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有会议数据
