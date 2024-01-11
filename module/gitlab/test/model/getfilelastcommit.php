#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::getFileLastCommit();
timeout=0
cid=1

- 空的路径和分支 @0
- 错误的分支 @0
- 正确的分支属性sha @1b9405639ddef9585b3743b0637b4f79775409b7
- 错误的路径 @0
- 正确的路径属性sha @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 带/的路径属性sha @0fd3978da3be5969ef39ff2517cc69cb3a23811c
- 带/的路径属性sha @0fd3978da3be5969ef39ff2517cc69cb3a23811c

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(1);

$gitlab = new gitlabTest();

$path   = '';
$branch = '';
r($gitlab->getFileLastCommitTest($path, $branch)) && p() && e('0'); // 空的路径和分支

$branch = 'test_error';
r($gitlab->getFileLastCommitTest($path, $branch)) && p() && e('0'); // 错误的分支

$branch = 'master';
r($gitlab->getFileLastCommitTest($path, $branch)) && p('sha') && e('1b9405639ddef9585b3743b0637b4f79775409b7'); // 正确的分支

$path = 'test_error';
r($gitlab->getFileLastCommitTest($path, $branch)) && p() && e('0'); // 错误的路径

$path = 'README.md';
r($gitlab->getFileLastCommitTest($path, $branch)) && p('sha') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 正确的路径

$path = '/public';
r($gitlab->getFileLastCommitTest($path, $branch)) && p('sha') && e('0fd3978da3be5969ef39ff2517cc69cb3a23811c'); // 带/的路径

$path = '/public/';
r($gitlab->getFileLastCommitTest($path, $branch)) && p('sha') && e('0fd3978da3be5969ef39ff2517cc69cb3a23811c'); // 带/的路径
