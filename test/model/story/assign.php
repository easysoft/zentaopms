#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->assign();
cid=1
pid=1

指派需求，查看返回的指派人信息 >> 2,test2
指派需求，查看返回的指派人信息 >> 4,
指派需求，查看返回的指派人信息 >> 5,user10
指派需求，查看返回的指派人信息 >> 10,admin
指派需求，查看返回的指派人信息 >> 100,user50
指派需求，查看返回的指派人信息 >> 101,pm1

*/

$story = new storyTest();

r($story->assignTest(2, 'test2'))    && p('id,assignedTo') && e('2,test2');    //指派需求，查看返回的指派人信息
r($story->assignTest(4, ''))         && p('id,assignedTo') && e('4,');         //指派需求，查看返回的指派人信息 
r($story->assignTest(5, 'user10'))   && p('id,assignedTo') && e('5,user10');   //指派需求，查看返回的指派人信息
r($story->assignTest(10, 'admin'))   && p('id,assignedTo') && e('10,admin');   //指派需求，查看返回的指派人信息
r($story->assignTest(100, 'user50')) && p('id,assignedTo') && e('100,user50'); //指派需求，查看返回的指派人信息
r($story->assignTest(101, 'pm1'))    && p('id,assignedTo') && e('101,pm1');    //指派需求，查看返回的指派人信息

