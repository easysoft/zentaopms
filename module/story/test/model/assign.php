#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(100);

/**

title=测试 storyModel->assign();
cid=1
pid=1

*/

$story = new storyTest();

r($story->assignTest(2, 'test2'))    && p('id,assignedTo') && e('2,test2');    //指派需求，查看返回的指派人信息
r($story->assignTest(4, ''))         && p('id,assignedTo') && e('4,~~');       //指派需求，查看返回的指派人信息
r($story->assignTest(5, 'user10'))   && p('id,assignedTo') && e('5,user10');   //指派需求，查看返回的指派人信息
r($story->assignTest(10, 'admin'))   && p('id,assignedTo') && e('10,admin');   //指派需求，查看返回的指派人信息
r($story->assignTest(100, 'user50')) && p('id,assignedTo') && e('100,user50'); //指派需求，查看返回的指派人信息
