#!/usr/bin/env php
<?php

/**

title=svnModel->setRepoRoot();
timeout=0
cid=1

- 查询SVN仓库信息属性repoRoot @https://svn.qc.oop.cc/svn/unittest

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();

$repo = $svn->repos[1];
$svn->setRepoRoot($repo);
r($svn) && p('repoRoot') && e('https://svn.qc.oop.cc/svn/unittest'); // 查询SVN仓库信息