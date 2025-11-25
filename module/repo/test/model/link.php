#!/usr/bin/env php
<?php

/**

title=测试 repoModel::link();
timeout=0
cid=18086

- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的BType属性 @story
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的AType属性 @revision
- 执行repoTest模块的linkTest方法，参数是1, $invalidRevision, 'story', 'repo', $validLinks  @失败
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $emptyLinks  @0
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'commit', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'task', 'repo', $validLinks 第0条的BType属性 @task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo', true)->gen(4);
zenData('repohistory')->loadYaml('repohistory')->gen(1);

$validRevision = 'c808480afe22d3a55d94e91c59a8f3170212ade0';
$invalidRevision = '22222';
$validLinks = array(1, 2);
$emptyLinks = array();

su('admin');

$repoTest = new repoTest();

r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:BType') && e('story');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:AType') && e('revision');
r($repoTest->linkTest(1, $invalidRevision, 'story', 'repo', $validLinks)) && p('') && e('失败');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $emptyLinks)) && p('') && e('0');
r($repoTest->linkTest(1, $validRevision, 'story', 'commit', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'task', 'repo', $validLinks)) && p('0:BType') && e('task');