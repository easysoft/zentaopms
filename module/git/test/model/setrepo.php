#!/usr/bin/env php
<?php

/**

title=gitModel->setRepo();
timeout=0
cid=1

- 查询Git仓库信息
 - 属性repoRoot @https://gitlabdev.qc.oop.cc/root/unittest1
 - 属性client @https://gitlabdev.qc.oop.cc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(1);
zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$git = $tester->loadModel('git');
$git->setRepos();

$repo = $git->repos[1];
$git->setRepo($repo);
r($git) && p('repoRoot,client') && e('https://gitlabdev.qc.oop.cc/root/unittest1,https://gitlabdev.qc.oop.cc'); // 查询Git仓库信息