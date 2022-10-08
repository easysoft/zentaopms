#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getStoryRelation();
cid=1
pid=1

获取用户需求25关联的软件需求数量 >> 1
获取软件需求26关联的用户需求数量 >> 1
获取用户需求25关联的软件需求详情 >> 软件需求26,story,active
获取软件需求26关联的用户需求详情 >> 用户需求25,requirement,draft

*/

global $tester;
$relations1 = $tester->loadModel('story')->getStoryRelation(25, 'requirement');
$relations2 = $tester->story->getStoryRelation(26, 'story');

r(count($relations1)) && p()                    && e('1');                            // 获取用户需求25关联的软件需求数量
r(count($relations2)) && p()                    && e('1');                            // 获取软件需求26关联的用户需求数量
r($relations1[0])     && p('title,type,status') && e('软件需求26,story,active');      // 获取用户需求25关联的软件需求详情
r($relations2[0])     && p('title,type,status') && e('用户需求25,requirement,draft'); // 获取软件需求26关联的用户需求详情