#!/usr/bin/env php
<?php

/**

title=测试 storyModel->createStoryFromGitlabIssue();
cid=18487

- 创建正常的需求，获取创建后的id、title、stage、product
 - 属性id @1
 - 属性title @测试需求1
 - 属性stage @projected
 - 属性product @1
- 需求名称为空，给出提示 @1
- 创建正常的需求，获取创建后的id、title、stage、product
 - 属性id @2
 - 属性title @测试需求3
 - 属性stage @projected
 - 属性product @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('story')->gen(0);
zenData('storyspec')->gen(0);
zenData('projectstory')->gen(0);

$story = new storyModelTest();
$story1               = new stdclass();
$story1->title        = '测试需求1';
$story1->pri          = '3';
$story1->product      = 1;
$story1->source       = 'feedback';
$story1->sourceNote   = '';
$story1->keywords     = '';
$story1->color        = '';
$story1->openedBy     = 'admin';
$story1->openedDate   = date('Y-m-d H:i:s');
$story1->spec         = '测试需求的描述111';
$story1->verify       = '测试需求的验收标准111';
$story1->estimate     = 3;
$story1->mailto       = 'user2,test2,admin';

$story2 = clone $story1;
$story2->title = '';

$story3 = clone $story1;
$story3->product = 2;
$story3->title   = '测试需求3';

$result1 = $story->createStoryFromGitlabIssueTest($story1, 11);
$result2 = $story->createStoryFromGitlabIssueTest($story2, 12);
$result3 = $story->createStoryFromGitlabIssueTest($story3, 0);

r($result1)                                             && p('id,title,stage,product') && e('1,测试需求1,projected,1');  //创建正常的需求，获取创建后的id、title、stage、product
r(str_contains($result2['title'][0], '名称』不能为空')) && p()                         && e('1');                        //需求名称为空，给出提示
r($result3)                                             && p('id,title,stage,product') && e('2,测试需求3,projected,2');  //创建正常的需求，获取创建后的id、title、stage、product
