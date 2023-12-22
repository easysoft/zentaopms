#!/usr/bin/env php
<?php

/**

title=gitModel->saveCommits();
timeout=0
cid=1

- 没有提交信息
 - 属性id @0
 - 属性commits @0
- 没有关联信息
 - 属性id @1
 - 属性commits @0
- 有关联信息
 - 属性id @1
 - 属性commits @0
- 一条没有关联信息，一条有关联信息
 - 属性id @1
 - 属性commits @0
- 一条没有关联信息，一条有关联信息，有关联构建
 - 属性id @1
 - 属性commits @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/git.class.php';

zdTable('repo')->config('repo')->gen(1);
zdTable('repohistory')->gen(0);
su('admin');

$git = new gitTest();

r($git->saveCommitsTest(1, 'empty')) && p('id,commits') && e('0,0'); // 没有提交信息
r($git->saveCommitsTest(1, 'nolink')) && p('id,commits') && e('1,0'); // 没有关联信息
r($git->saveCommitsTest(1, 'linked')) && p('id,commits') && e('1,0'); // 有关联信息

r($git->saveCommitsTest(1, 'nolink|linked'))     && p('id,commits') && e('1,0'); // 一条没有关联信息，一条有关联信息
r($git->saveCommitsTest(1, 'nolink|linked|job')) && p('id,commits') && e('1,0'); // 一条没有关联信息，一条有关联信息，有关联构建