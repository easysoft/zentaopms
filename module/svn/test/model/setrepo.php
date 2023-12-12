#!/usr/bin/env php
<?php

/**

title=svnModel->setRepo();
timeout=0
cid=1

- 查询SVN仓库信息
 - 属性repoRoot @http://10.0.7.237/svn/repo
 - 属性client @/usr/bin/svn --non-interactive --username user --password 123456 --no-auth-cache

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();

$repo = $svn->repos[1];
$svn->setRepo($repo);
r($svn) && p('repoRoot,client') && e('http://10.0.7.237/svn/repo,/usr/bin/svn --non-interactive --username user --password 123456 --no-auth-cache'); // 查询SVN仓库信息