#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/repo.class.php';
su('admin');

/**

title=测试 repoModel->addLink();
cid=1
pid=1

*/

$repo = new repoTest();

$commentTask  = 'Finish task #1.';
$commentStory = 'Effort story #1 Cost 1h.';
$commentBug   = 'Fix bug #1.';
$commentElse  = 'Anything else.';
r($repo->addLinkTest($commentTask,  'task'))  && p('') && e("task #<a href='atask-view-1.' >1</a>");   //任务链接
r($repo->addLinkTest($commentStory, 'story')) && p('') && e("story #<a href='astory-view-1.' >1</a>"); //需求链接
r($repo->addLinkTest($commentBug,   'bug'))   && p('') && e("bug #<a href='abug-view-1.' >1</a>");     //Bug链接
r($repo->addLinkTest($commentElse,  'task'))  && p('') && e("empty");                                  //无链接
