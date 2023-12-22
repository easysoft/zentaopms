#!/usr/bin/env php
<?php

/**

title=gitModel->getRepoTags();
timeout=0
cid=1

- 查询tag信息 @tag1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$git = $tester->loadModel('git');
$git->setRepos();

$repo = $git->repos[1];
r($git->getRepoTags($repo)) && p('0') && e('tag1'); // 查询tag信息