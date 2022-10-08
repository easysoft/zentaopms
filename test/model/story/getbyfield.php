#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getByField();
cid=1
pid=1

根据指派给获取软件需求 >> 用户需求1,requirement,admin
根据创建人获取软件需求 >> 软件需求6,user2
根据由谁评审获取软件需求 >> 软件需求12,admin
根据由谁评审获取软件需求数量 >> 2
根据由谁评审获取软件需求 >> 软件需求306,story
根据由谁关闭获取软件需求 >> 用户需求23,requirement,test3,关闭原因23
根据状态获取软件需求 >> 软件需求22,active,story
根据计划获取软件需求 >> 软件需求26,projected,7,19
根据计划获取软件需求数量 >> 2

*/

global $tester;
$tester->loadModel('story');
$byAssignedTo = $tester->story->getByField(1,  0, array(), 'assignedTo', 'admin', 'requirement', 'id_desc');
$byOpenedBy   = $tester->story->getByField(2,  0, array(), 'openedBy', 'user2', 'story', 'id_asc');
$byReviewedBy = $tester->story->getByField(3,  0, array(), 'reviewedBy', 'admin', 'story', 'id_desc');
$byReviewBy   = $tester->story->getByField(77, 0, array(), 'reviewBy', 'admin', 'story', 'id_desc');
$byClosedBy   = $tester->story->getByField(6,  0, array(), 'closedBy', 'test3', 'requirement', 'id_desc');
$byStatus     = $tester->story->getByField(6,  0, array(), 'status', 'active', 'story', 'id_desc');
$byPlan       = $tester->story->getByField(7,  0, array(), 'plan', '19', 'story', 'id_desc');

r($byAssignedTo)        && p('1:title,type,assignedTo')             && e('用户需求1,requirement,admin');             // 根据指派给获取软件需求
r($byOpenedBy)          && p('6:title,openedBy')                    && e('软件需求6,user2');                         // 根据创建人获取软件需求
r($byReviewedBy)        && p('12:title,reviewedBy')                 && e('软件需求12,admin');                        // 根据由谁评审获取软件需求
r(count($byReviewBy))   && p()                                      && e('2');                                       // 根据由谁评审获取软件需求数量
r($byReviewBy)          && p('306:title,type')                      && e('软件需求306,story');                       // 根据由谁评审获取软件需求
r($byClosedBy)          && p('23:title,type,closedBy,closedReason') && e('用户需求23,requirement,test3,关闭原因23'); // 根据由谁关闭获取软件需求
r($byStatus)            && p('22:title,status,type')                && e('软件需求22,active,story');                 // 根据状态获取软件需求
r($byPlan)              && p('26:title,stage,product,plan')         && e('软件需求26,projected,7,19');               // 根据计划获取软件需求
r(count($byPlan))       && p()                                      && e('2');                                       // 根据计划获取软件需求数量