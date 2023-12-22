#!/usr/bin/env php
<?php

/**

title=gitModel->setRepoRoot();
timeout=0
cid=1

- 查询Git仓库信息属性repoRoot @https://gitlabdev.qc.oop.cc/root/unittest1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$git = $tester->loadModel('git');
$git->setRepos();

$repo = $git->repos[1];
$git->setRepoRoot($repo);
r($git) && p('repoRoot') && e('https://gitlabdev.qc.oop.cc/root/unittest1'); // 查询Git仓库信息