#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getUserStories();
cid=1
pid=1

获取指派给User2的所有需求数量 >> 100
获取指派给User2的ID为6的需求详情 >> 软件需求6,story,user2,projected
获取由User2创建的需求数量，每页10条 >> 10
获取由User2创建的ID为38的需求详情 >> 软件需求38,story,user2,released
获取由Test3关闭的所有需求数量 >> 14
获取由Test3关闭的ID为203的需求详情 >> 用户需求203,requirement,test3,
获取无评审人的需求数量 >> 25
获取无评审人的ID为402的需求详情 >> 软件子需求2,story,,planned

*/

global $tester;
$tester->loadModel('story');
$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);

$assignUser2Stories     = $tester->story->getUserStories('user2', 'assignedTo');
$openedByUser2Stories   = $tester->story->getUserStories('user2', 'openedBy', 'id_asc', $pager, 'story', true);
$closedByTest3Stories   = $tester->story->getUserStories('test3', 'closedBy', 'id_asc', null, 'requirement', true);
$emptyReviewedByStories = $tester->story->getUserStories('', 'reviewedBy');

r(count($assignUser2Stories))     && p()                                  && e('100');                             //获取指派给User2的所有需求数量
r($assignUser2Stories)            && p('6:title,type,assignedTo,stage')   && e('软件需求6,story,user2,projected'); //获取指派给User2的ID为6的需求详情
r(count($openedByUser2Stories))   && p()                                  && e('10');                              //获取由User2创建的需求数量，每页10条
r($openedByUser2Stories)          && p('38:title,type,openedBy,stage')    && e('软件需求38,story,user2,released'); //获取由User2创建的ID为38的需求详情
r(count($closedByTest3Stories))   && p()                                  && e('14');                              //获取由Test3关闭的所有需求数量
r($closedByTest3Stories)          && p('203:title,type,closedBy,stage')   && e('用户需求203,requirement,test3,');  //获取由Test3关闭的ID为203的需求详情
r(count($emptyReviewedByStories)) && p()                                  && e('25');                              //获取无评审人的需求数量
r($emptyReviewedByStories)        && p('402:title,type,reviewedBy,stage') && e('软件子需求2,story,,planned');      //获取无评审人的ID为402的需求详情