#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getByField();
cid=18502

- 根据指派给获取软件需求
 - 第1条的title属性 @用户需求1
 - 第1条的type属性 @requirement
 - 第1条的assignedTo属性 @admin
- 根据创建人获取软件需求
 - 第6条的title属性 @软件需求6
 - 第6条的openedBy属性 @user2
- 根据由谁评审获取软件需求
 - 第12条的title属性 @软件需求12
 - 第12条的reviewedBy属性 @admin
- 根据由谁评审获取软件需求数量 @1
- 根据由谁评审获取软件需求
 - 第40条的title属性 @软件需求40
 - 第40条的type属性 @story
- 根据由谁关闭获取软件需求
 - 第23条的title属性 @用户需求23
 - 第23条的type属性 @requirement
 - 第23条的closedBy属性 @test3
 - 第23条的closedReason属性 @subdivided
- 根据状态获取软件需求
 - 第22条的title属性 @软件需求22
 - 第22条的status属性 @active
 - 第22条的type属性 @story
- 根据计划获取软件需求
 - 第26条的title属性 @软件需求26
 - 第26条的stage属性 @projected
 - 第26条的product属性 @7
 - 第26条的plan属性 @19
- 根据计划获取软件需求数量 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->status->range('draft,active,closed,changing,reviewing');
$story->gen(100);
$storyreview = zenData('storyreview');
$storyreview->story->range('1-100');
$storyreview->gen(100);

global $tester;
$tester->loadModel('story');
$byAssignedTo = $tester->story->getByField(1,  0, array(), 'assignedTo', 'admin', 'requirement', 'id_desc');
$byOpenedBy   = $tester->story->getByField(2,  0, array(), 'openedBy', 'user2', 'story', 'id_asc');
$byReviewedBy = $tester->story->getByField(3,  0, array(), 'reviewedBy', 'admin', 'story', 'id_desc');
$byReviewBy   = $tester->story->getByField(10, 0, array(), 'reviewBy', 'admin', 'story', 'id_desc');
$byClosedBy   = $tester->story->getByField(6,  0, array(), 'closedBy', 'test3', 'requirement', 'id_desc');
$byStatus     = $tester->story->getByField(6,  0, array(), 'status', 'active', 'story', 'id_desc');
$byPlan       = $tester->story->getByField(7,  0, array(), 'plan', '19', 'story', 'id_desc');

r($byAssignedTo)        && p('1:title,type,assignedTo')             && e('用户需求1,requirement,admin');             // 根据指派给获取软件需求
r($byOpenedBy)          && p('6:title,openedBy')                    && e('软件需求6,user2');                         // 根据创建人获取软件需求
r($byReviewedBy)        && p('12:title,reviewedBy')                 && e('软件需求12,admin');                        // 根据由谁评审获取软件需求
r(count($byReviewBy))   && p()                                      && e('1');                                       // 根据由谁评审获取软件需求数量
r($byReviewBy)          && p('40:title,type')                       && e('软件需求40,story');                        // 根据由谁评审获取软件需求
r($byClosedBy)          && p('23:title,type,closedBy,closedReason') && e('用户需求23,requirement,test3,subdivided'); // 根据由谁关闭获取软件需求
r($byStatus)            && p('22:title,status,type')                && e('软件需求22,active,story');                 // 根据状态获取软件需求
r($byPlan)              && p('26:title,stage,product,plan')         && e('软件需求26,projected,7,19');               // 根据计划获取软件需求
r(count($byPlan))       && p()                                      && e('2');                                       // 根据计划获取软件需求数量
