#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->createStoryFromGitlabIssue();
cid=1
pid=1

创建正常的需求，获取创建后的id、title、stage、product >> 401,测试需求1,projected,1
需求名称为空，给出提示 >> 『研发需求名称』不能为空。
创建正常的需求，获取创建后的id、title、stage、product >> 402,测试需求3,projected,2

*/

$story = new storyTest();
$story1->title    = '测试需求1'; 
$story1->pri      = '3'; 
$story1->product  = 1; 
$story1->spec     = '测试需求的描述111'; 
$story1->verify   = '测试需求的验收标准111'; 
$story1->estimate = 3; 
$story1->mailto   = array('user2', 'test2', 'admin'); 

$story2 = clone $story1;
$story2->title = '';

$story3 = clone $story1;
$story3->product = 2;
$story3->title   = '测试需求3';

$result1 = $story->createStoryFromGitlabIssueTest($story1, 11);
$result2 = $story->createStoryFromGitlabIssueTest($story2, 12);
$result3 = $story->createStoryFromGitlabIssueTest($story3, 0);

r($result1) && p('id,title,stage,product') && e('401,测试需求1,projected,1');  //创建正常的需求，获取创建后的id、title、stage、product
r($result2) && p('title:0')                && e('『研发需求名称』不能为空。'); //需求名称为空，给出提示
r($result3) && p('id,title,stage,product') && e('402,测试需求3,projected,2');  //创建正常的需求，获取创建后的id、title、stage、product
