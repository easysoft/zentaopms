#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasScrumIssueData();
timeout=0
cid=15911

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->model->range('scrum');
$projectTable->gen(5);

zenData('issue')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasIssueDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无问题数据
r($customTester->hasIssueDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无问题数据
r($customTester->hasIssueDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无问题数据

$issueTable = zenData('issue');
$issueTable->deleted->range('0');
$issueTable->project->range('1-5');
$issueTable->gen(5);
r($customTester->hasIssueDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有问题数据
r($customTester->hasIssueDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有问题数据
r($customTester->hasIssueDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有问题数据
