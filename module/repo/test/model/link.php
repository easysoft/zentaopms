#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

/**

title=测试 repoModel->link();
timeout=0
cid=1

- 关联需求第0条的relation属性 @commit
- 关联bug第0条的BType属性 @bug
- 关联task第0条的BType属性 @task
- 关联错误的revision @失败

*/

zdTable('repo')->config('repo', true)->gen(4);
zdTable('repohistory')->config('repohistory')->gen(1);

$revision = 'c808480afe22d3a55d94e91c59a8f3170212ade0';
$links    = array(1, 2);

$repo = new repoTest();
r($repo->linkTest(1, $revision, 'story', 'repo', $links)) && p('0:relation') && e('commit'); //关联需求
r($repo->linkTest(1, $revision, 'bug', 'repo', $links))   && p('0:BType')    && e('bug'); //关联bug
r($repo->linkTest(1, $revision, 'task', 'repo', $links))  && p('0:BType')    && e('task'); //关联task
r($repo->linkTest(1, '22222', 'task', 'repo', $links))    && p('')           && e('失败'); //关联错误的revision