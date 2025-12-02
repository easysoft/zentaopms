#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getUserStories();
cid=18566

- 获取指派给User2的所有需求数量 @103
- 获取指派给User2的ID为6的需求详情
- 第6条的title属性 @软件需求6
- 第6条的type属性 @story
- 第6条的assignedTo属性 @user2
- 第6条的stage属性 @projected
- 获取由User2创建的需求数量，每页10条 @10
- 获取由User2创建的ID为38的需求详情
- 第38条的title属性 @软件需求38
- 第38条的type属性 @story
- 第38条的openedBy属性 @user2
- 第38条的stage属性 @released
- 获取由Test3关闭的所有需求数量 @41
- 获取由Test3关闭的ID为203的需求详情
- 第203条的title属性 @用户需求203
- 第203条的type属性 @requirement
- 第203条的closedBy属性 @test3
- 第203条的stage属性 @~~
- 获取无评审人的需求数量 @6
- 获取无评审人的ID为402的需求详情
- 第402条的title属性 @软件需求402
- 第402条的type属性 @story
- 第402条的reviewedBy属性 @~~
- 第402条的stage属性 @wait
- 获取admin评审人的需求数量 @0
- 获取admin评审人的ID为2的需求详情 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->gen(410);
zenData('product')->gen(100);
$storyreview = zenData('storyreview');
$storyreview->story->range('1-1000');
$storyreview->gen(100);

global $tester, $app;
$tester->loadModel('story');
$app->moduleName = 'story';
$app->methodName = 'getUserStories';
$app->rawModule  = 'story';
$app->rawMethod  = 'getUserStories';
$tester->app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);
$tester->story->dao->update(TABLE_STORY)->set('reviewedBy')->eq('')->where('id')->ge('400')->exec();

$assignUser2Stories     = $tester->story->getUserStories('user2', 'assignedTo');
$openedByUser2Stories   = $tester->story->getUserStories('user2', 'openedBy', 'id_asc', $pager, 'story', true);
$closedByTest3Stories   = $tester->story->getUserStories('test3', 'closedBy', 'id_asc', null, 'requirement', true);
$emptyReviewedByStories = $tester->story->getUserStories('', 'reviewedBy');
$emptyReviewByStories   = $tester->story->getUserStories('admin', 'reviewBy');

r(count($assignUser2Stories))     && p()                                  && e('103');                              //获取指派给User2的所有需求数量
r($assignUser2Stories)            && p('6:title,type,assignedTo,stage')   && e('软件需求6,story,user2,projected');  //获取指派给User2的ID为6的需求详情
r(count($openedByUser2Stories))   && p()                                  && e('10');                               //获取由User2创建的需求数量，每页10条
r($openedByUser2Stories)          && p('38:title,type,openedBy,stage')    && e('软件需求38,story,user2,released');  //获取由User2创建的ID为38的需求详情
r(count($closedByTest3Stories))   && p()                                  && e('41');                               //获取由Test3关闭的所有需求数量
r($closedByTest3Stories)          && p('203:title,type,closedBy,stage')   && e('用户需求203,requirement,test3,~~'); //获取由Test3关闭的ID为203的需求详情
r(count($emptyReviewedByStories)) && p()                                  && e('6');                                //获取无评审人的需求数量
r($emptyReviewedByStories)        && p('402:title,type,reviewedBy,stage') && e('软件需求402,story,~~,wait');        //获取无评审人的ID为402的需求详情
r(count($emptyReviewByStories))   && p()                                  && e('0');                                //获取admin评审人的需求数量
r($emptyReviewByStories)          && p()                                  && e('0');                                //获取admin评审人的ID为2的需求详情
