#!/usr/bin/env php
<?php

/**

title=svnModel->getRepoTags();
timeout=0
cid=1

- 查询目录信息属性/tag @tag
- 查询没有子目录的信息 @0
- 查询错误目录的信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();

$repo = $svn->repos[1];
r($svn->getRepoTags($repo, ''))      && p('/tag') && e('tag'); // 查询目录信息
r($svn->getRepoTags($repo, 'tag'))   && p('')     && e('0');   // 查询没有子目录的信息
r($svn->getRepoTags($repo, 'error')) && p('')     && e('0');   // 查询错误目录的信息