#!/usr/bin/env php
<?php

/**

title=测试 repoModel::addLink();
timeout=0
cid=18028

- 执行repo模块的addLinkTest方法，参数是$singleTaskComment, 'task'  @task #<a href='task-view-1.html'  >1</a>
- 执行repo模块的addLinkTest方法，参数是$multiTaskComment, 'task'  @task #<a href='task-view-1.html'  >1</a>
- 执行repo模块的addLinkTest方法，参数是$storyComment, 'story'  @story #<a href='story-view-1.html'  >1</a>
- 执行repo模块的addLinkTest方法，参数是$bugComment, 'bug'  @bug #<a href='bug-view-1.html'  >1</a>
- 执行repo模块的addLinkTest方法，参数是$mixedComment, 'task'  @task #<a href='task-view-1.html'  >1</a>
- 执行repo模块的addLinkTest方法，参数是$noMatchComment, 'task'  @empty
- 执行repo模块的addLinkTest方法，参数是$specialFormatComment, 'task'  @Task #<a href='task-view-10.html'  >10</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

// 测试数据准备
$singleTaskComment = 'Finish task #1.';
$multiTaskComment = 'Fix task #1, #2, #3.';
$storyComment = 'Effort story #1 Cost 1h.';
$bugComment = 'Fix bug #1.';
$mixedComment = 'Fix task #1 and story #2 and bug #3.';
$noMatchComment = 'Anything else without ID.';
$specialFormatComment = 'Task #10, #20 and #30 done.';

r($repo->addLinkTest($singleTaskComment, 'task')) && p() && e("task #<a href='task-view-1.html'  >1</a>");
r($repo->addLinkTest($multiTaskComment, 'task')) && p() && e("task #<a href='task-view-1.html'  >1</a>");
r($repo->addLinkTest($storyComment, 'story')) && p() && e("story #<a href='story-view-1.html'  >1</a>");
r($repo->addLinkTest($bugComment, 'bug')) && p() && e("bug #<a href='bug-view-1.html'  >1</a>");
r($repo->addLinkTest($mixedComment, 'task')) && p() && e("task #<a href='task-view-1.html'  >1</a>");
r($repo->addLinkTest($noMatchComment, 'task')) && p() && e("empty");
r($repo->addLinkTest($specialFormatComment, 'task')) && p() && e("Task #<a href='task-view-10.html'  >10</a>");