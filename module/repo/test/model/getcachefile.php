#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->getCacheFile();
timeout=0
cid=1

- 测试生成缓存文件 @1

*/

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

$repoID   = 1;
$path     = '';
$revision = '';

$repo = new repoTest();
r($repo->getCacheFileTest($repoID, $path, $revision)) && p() && e('1'); //测试生成缓存文件