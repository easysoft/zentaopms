#!/usr/bin/env php
<?php

/**

title=svnModel->setRepo();
timeout=0
cid=1

- 查询SVN仓库信息
 - 属性repoRoot @https://svn.qc.oop.cc/svn/unittest
 - 属性client @svn --non-interactive --trust-server-cert --username admin --password KXdOi8zgTcUqEFX2Hx8B --no-auth-cache

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();

$repo = $svn->repos[1];
$svn->setRepo($repo);
r($svn) && p('repoRoot,client') && e('https://svn.qc.oop.cc/svn/unittest,svn --non-interactive --trust-server-cert --username admin --password KXdOi8zgTcUqEFX2Hx8B --no-auth-cache'); // 查询SVN仓库信息
