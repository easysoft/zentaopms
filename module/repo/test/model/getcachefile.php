#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCacheFile();
timeout=0
cid=1

- 执行repo模块的getCacheFileTest方法  @

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repoID   = 1;
$path     = '';
$revision = '';

$repo = new repoTest();
r($repo->getCacheFileTest($repoID, $path, $revision)) && p() && e('1'); //测试生成缓存文件
