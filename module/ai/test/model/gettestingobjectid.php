#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTestingObjectId();
timeout=0
cid=25074

- 步骤1：获取产品模块测试对象ID @5
- 步骤2：获取项目模块测试对象ID @5
- 步骤3：获取执行模块测试对象ID @13
- 步骤4：获取业务需求测试对象ID @2
- 步骤5：获取用户需求测试对象ID @5
- 步骤6：获取软件需求测试对象ID @9
- 步骤7：未知模块返回0 @0
- 步骤8：空prompt返回0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
$storyTable = zenData('story');
$storyTable->product->range('1-5');
$storyTable->type->range('epic{2},requirement{3},story{4}');
$storyTable->gen(9);

su('admin');

global $app;
if(!isset($app->user->view)) $app->user->view = new stdclass();
$app->user->view->products = '1,2,3,4,5';
$app->user->view->projects = '1,2,3,4,5';
$app->user->view->sprints  = '2,7,13';

$aiTest = new aiModelTest();

$productPrompt = (object)array('module' => 'product', 'actionPurpose' => '');
$projectPrompt = (object)array('module' => 'project', 'actionPurpose' => '');
$sprintPrompt  = (object)array('module' => 'execution', 'actionPurpose' => '');
$epicPrompt    = (object)array('module' => 'epic', 'actionPurpose' => '');
$URPrompt      = (object)array('module' => 'requirement', 'actionPurpose' => '');
$SRPrompt      = (object)array('module' => 'story', 'actionPurpose' => '');
$unknownPrompt = (object)array('module' => 'unknown', 'actionPurpose' => '');

r($aiTest->getTestingObjectIdTest($productPrompt)) && p() && e('5');  // 步骤1：获取产品模块测试对象ID
r($aiTest->getTestingObjectIdTest($projectPrompt)) && p() && e('5');  // 步骤2：获取项目模块测试对象ID
r($aiTest->getTestingObjectIdTest($sprintPrompt))  && p() && e('13'); // 步骤3：获取执行模块测试对象ID
r($aiTest->getTestingObjectIdTest($epicPrompt))    && p() && e('2');  // 步骤4：获取业务需求测试对象ID
r($aiTest->getTestingObjectIdTest($URPrompt))      && p() && e('5');  // 步骤5：获取用户需求测试对象ID
r($aiTest->getTestingObjectIdTest($SRPrompt))      && p() && e('9');  // 步骤6：获取软件需求测试对象ID
r($aiTest->getTestingObjectIdTest($unknownPrompt)) && p() && e('0');  // 步骤7：未知模块返回0
r($aiTest->getTestingObjectIdTest(null))           && p() && e('0');  // 步骤8：空prompt返回0
