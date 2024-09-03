#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->version->range('1');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->status->range('active');
$story->plan->range('0');
$story->gen(5);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1');
$storyspec->spec->range('需求描述');
$storyspec->title->range('需求1,需求2,需求3,需求4,需求5');
$storyspec->gen(5);

$tester = new linkStoryTester();
$tester->login();
$planID['planID'] = '2';

r($tester->linkStory($planID))      && p('message,status') && e('关联需求成功,SUCCESS');//关联需求
r($tester->unlinkStory($planID))    && p('message,status') && e('移除单个需求成功,SUCCESS');//移除单个需求
r($tester->unlinkAllStory($planID)) && p('message,status') && e('移除全部需求成功,SUCCESS');//移除全部需求

$tester->closeBrowser();
