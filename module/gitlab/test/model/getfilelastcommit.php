#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::getFileLastCommit();
timeout=0
cid=1

- 获取版本库1 unitfile文件最后一次提交信息
 - 属性message @unitfile
 - 属性authorName @Administrator
- 获取版本库1 public文件夹最后一次提交信息
 - 属性message @2023-12-21
 - 属性authorName @Administrator
- 获取版本库1 public文件夹最后一次提交信息
 - 属性message @getFileLastCommit单测
 - 属性authorName @Administrator

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$gitlab = new gitlabTest();

$repoID = 1;
$path   = 'unitfile';
$path2  = 'public';
$path3  = 'getfilelastcommit';
$branch = 'branch1';

r($gitlab->getFileLastCommitTest($repoID, $path))           && p('message,authorName') && e('unitfile,Administrator'); //获取版本库1 unitfile文件最后一次提交信息
r($gitlab->getFileLastCommitTest($repoID, $path2))          && p('message,authorName') && e('2023-12-21,Administrator'); //获取版本库1 public文件夹最后一次提交信息
r($gitlab->getFileLastCommitTest($repoID, $path3, $branch)) && p('message,authorName') && e('getFileLastCommit单测,Administrator'); //获取版本库1 public文件夹最后一次提交信息
