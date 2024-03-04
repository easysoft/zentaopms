#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->addLink();
timeout=0
cid=1

- 任务链接 @task #<a href='task-view-1.html'  >1</a>
- 需求链接 @story #<a href='story-view-1.html'  >1</a>
- Bug链接 @bug #<a href='bug-view-1.html'  >1</a>
- 无链接 @empty

*/

$repo = new repoTest();

$commentTask  = 'Finish task #1.';
$commentStory = 'Effort story #1 Cost 1h.';
$commentBug   = 'Fix bug #1.';
$commentElse  = 'Anything else.';
r($repo->addLinkTest($commentTask,  'task'))  && p() && e("task #<a href='task-view-1.html'  >1</a>");   //任务链接
r($repo->addLinkTest($commentStory, 'story')) && p() && e("story #<a href='story-view-1.html'  >1</a>"); //需求链接
r($repo->addLinkTest($commentBug,   'bug'))   && p() && e("bug #<a href='bug-view-1.html'  >1</a>");     //Bug链接
r($repo->addLinkTest($commentElse,  'task'))  && p() && e("empty");                                  //无链接