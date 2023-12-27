#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCommitsByObject();
timeout=0
cid=8

- 获取任务关联信息第0条的id属性 @1
- 获取bug关联信息第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
- 获取需求关联信息第0条的comment属性 @代码注释

*/

zdTable('task')->gen(10);
zdTable('bug')->gen(10);
zdTable('story')->gen(10);
zdTable('relation')->config('relation')->gen(3);
zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(1);

$repo = $tester->loadModel('repo');

$taskID  = 8;
$bugID   = 4;
$storyID = 10;

r($repo->getCommitsByObject($taskID, 'task'))   && p('0:id')       && e('1'); //获取任务关联信息
r($repo->getCommitsByObject($bugID, 'bug'))     && p('0:revision') && e('c808480afe22d3a55d94e91c59a8f3170212ade0'); //获取bug关联信息
r($repo->getCommitsByObject($storyID, 'story')) && p('0:comment')  && e('代码注释'); //获取需求关联信息