#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

/**

title=测试 repoModel->unlink();
timeout=0
cid=1

- 取消关联需求 @success
- 取消关联bug @success
- 取消关联任务 @success

*/

zdTable('repo')->config('repo', true)->gen(4);

$revision = '398062532b051dac820345f8952c84f957dd9fa0';
$links = array(1, 2);

$repo = new repoTest();
r($repo->unlinkTest(1, $revision, 'story', 1)) && p('') && e('success'); //取消关联需求
r($repo->unlinkTest(1, $revision, 'bug', 1))   && p('') && e('success'); //取消关联bug
r($repo->unlinkTest(1, $revision, 'task', 1))  && p('') && e('success'); //取消关联任务