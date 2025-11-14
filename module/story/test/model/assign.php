#!/usr/bin/env php
<?php

/**

title=测试 storyModel->assign();
timeout=0
cid=18463

- 指派需求，查看返回的指派人信息
 - 属性id @2
 - 属性assignedTo @test2
- 指派需求，查看返回的指派人信息
 - 属性id @4
 - 属性assignedTo @~~
- 指派需求，查看返回的指派人信息
 - 属性id @5
 - 属性assignedTo @user10
- 指派需求，查看返回的指派人信息
 - 属性id @10
 - 属性assignedTo @admin
- 指派需求，查看返回的指派人信息
 - 属性id @100
 - 属性assignedTo @user50

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('product')->gen(1);
$story = zenData('story');
$story->product->range(1);
$story->gen(100);

$story = new storyTest();

r($story->assignTest(2, 'test2'))    && p('id,assignedTo') && e('2,test2');    //指派需求，查看返回的指派人信息
r($story->assignTest(4, ''))         && p('id,assignedTo') && e('4,~~');       //指派需求，查看返回的指派人信息
r($story->assignTest(5, 'user10'))   && p('id,assignedTo') && e('5,user10');   //指派需求，查看返回的指派人信息
r($story->assignTest(10, 'admin'))   && p('id,assignedTo') && e('10,admin');   //指派需求，查看返回的指派人信息
r($story->assignTest(100, 'user50')) && p('id,assignedTo') && e('100,user50'); //指派需求，查看返回的指派人信息