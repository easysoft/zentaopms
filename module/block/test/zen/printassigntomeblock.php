#!/usr/bin/env php
<?php
/**

title=测试 blockZen::printAssignToMeBlock();
timeout=0
cid=1

- 执行$count @12
- 执行$count['review'] @5
- 执行$count['todo'] @1
- 执行$count['task'] @0
- 执行$count['bug'] @10
- 执行$count['story'] @3
- 执行$count['requirement'] @3
- 执行$count['risk'] @1
- 执行$count['issue'] @0
- 执行$count['feedback'] @0
- 执行$count['ticket'] @0
- 执行$count['reviewissue'] @0
- 执行$count['meeting'] @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('task')->gen(10);
$story = zenData('story');
$story->type->range('story,requirement,epic');
$story->gen(30);
zenData('bug')->gen(10);
zenData('case')->gen(10);
zenData('testtask')->gen(10);
zenData('risk')->gen(10);
$reviewissue = zenData('reviewissue');
$reviewissue->project->range('1');
$reviewissue->review->range('1-5');
$reviewissue->type->range('review');
$reviewissue->opinionDate->range('`2025-01-01`');
$reviewissue->resolutionDate->range('`2025-01-01`');
$reviewissue->createdDate->range('`2025-01-01`');
$reviewissue->gen(5);
zenData('nc')->gen(0);
zenData('review')->gen(10);
zenData('auditplan')->gen(10);
zenData('meeting')->gen(10);
zenData('feedback')->gen(10);
zenData('ticket')->gen(10);
zenData('demand')->gen(10);
zenData('todo')->gen(10);
zenData('approval')->gen(10);
su('admin');

global $config, $tester;
$config->URAndSR  = 1;
$config->enableER = 1;
$config->edition  = 'ipd';

$block = new stdclass();
$block->params = json_decode('{"todoCount":"20","taskCount":"20","bugCount":"20","riskCount":"20","issueCount":"20","storyCount":"20","reviewCount":"20","meetingCount":"20","feedbackCount":"20"}');

$tester->app->setModuleName('block');
$tester->app->rawModule = 'block';
$tester->app->rawMethod = 'printBlock';

$zenTest = initReference('block');
$method  = $zenTest->getMethod('printAssignToMeBlock');
$method->setAccessible(true);
$count    = $method->invokeArgs($zenTest->newInstance(), array($block));
r(count($count))         && p() && e('12');
r($count['review'])      && p() && e('5');
r($count['todo'])        && p() && e('1');
r($count['task'])        && p() && e('0');
r($count['bug'])         && p() && e('10');
r($count['story'])       && p() && e('3');
r($count['requirement']) && p() && e('3');
r($count['risk'])        && p() && e('1');
r($count['issue'])       && p() && e('0');
r($count['feedback'])    && p() && e('0');
r($count['ticket'])      && p() && e('0');
r($count['reviewissue']) && p() && e('0');
r($count['meeting'])     && p() && e('0');
