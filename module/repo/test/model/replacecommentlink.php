#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->replaceCommentLink();
timeout=0
cid=1

- 替换完成任务 @Finish task #<a href='task-view-8.html'  >8</a>.
- 替换完成多个任务 @Finish task #<a href='task-view-1.html'  >1</a>,<a href='task-view-8.html'  >8</a>,<a href='task-view-12.html'  >12</a>.

- 替换修复bug @Fix bug #<a href='bug-view-3.html'  >3</a>
- 替换修复多个bug @Fix bug #<a href='bug-view-3.html'  >3</a>,<a href='bug-view-5.html'  >5</a>,<a href='bug-view-12.html'  >12</a>

- 替换需求 @Story #<a href='story-view-1.html'  >1</a>
- 替换多个需求 @Story #<a href='story-view-1.html'  >1</a>,<a href='story-view-2.html'  >2</a>,<a href='story-view-3.html'  >3</a>

*/

$repo = new repotest();

$finishTaskComment  = 'Finish task#8.';
$finishTaskComment2 = 'Finish task#1,8,12.';
$fixBugComment      = 'Fix bug#3';
$fixBugComment2     = 'Fix bug#3,5,12';
$storyComment       = 'Story#1';
$storyComment2      = 'Story#1,2,3';

r($repo->replaceCommentLinkTest($finishTaskComment))  && p() && e("Finish task #<a href='task-view-8.html'  >8</a>."); //替换完成任务
r($repo->replaceCommentLinkTest($finishTaskComment2)) && p() && e("Finish task #<a href='task-view-1.html'  >1</a>,<a href='task-view-8.html'  >8</a>,<a href='task-view-12.html'  >12</a>."); //替换完成多个任务
r($repo->replaceCommentLinkTest($fixBugComment))      && p() && e("Fix bug #<a href='bug-view-3.html'  >3</a>"); //替换修复bug
r($repo->replaceCommentLinkTest($fixBugComment2))     && p() && e("Fix bug #<a href='bug-view-3.html'  >3</a>,<a href='bug-view-5.html'  >5</a>,<a href='bug-view-12.html'  >12</a>"); //替换修复多个bug
r($repo->replaceCommentLinkTest($storyComment))       && p() && e("Story #<a href='story-view-1.html'  >1</a>"); //替换需求
r($repo->replaceCommentLinkTest($storyComment2))      && p() && e("Story #<a href='story-view-1.html'  >1</a>,<a href='story-view-2.html'  >2</a>,<a href='story-view-3.html'  >3</a>"); //替换多个需求