#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getFileTree();
timeout=0
cid=8

- 获取代码文件得提交信息第一个文件
 - 第0条的parent属性 @0
 - 第0条的name属性 @LICENSE
 - 第0条的path属性 @LICENSE
- 获取代码文件得提交信息数量 @1
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFn
 - 第0条的name属性 @tag
 - 第0条的parent属性 @0
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFnJTJGUkVBRE1FLm1k
 - 第0条的name属性 @README.md
 - 第0条的parent属性 @dGFn
- 获取svn代码文件得提交信息数量 @1

*/

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(5);
zdTable('repohistory')->config('repohistory')->gen(6);
zdTable('repofiles')->config('repofiles')->gen(7);

$repo = new repoTest();

$svnID    = 4;
$giteaID  = 3;
$branch   = 'branch3';

$result = $repo->getFileTreeTest($giteaID, '');
r($result)            && p('0:parent,name,path') && e('0,LICENSE,LICENSE'); //获取代码文件得提交信息第一个文件
r(count($result) > 1) && p()                     && e('1'); //获取代码文件得提交信息数量

$result = $repo->getFileTreeTest($svnID, '');
r($result)                          && p('0:id,name,parent') && e('dGFn,tag,0'); //获取svn代码文件得提交信息第一个文件夹信息
r($result[0]['children'])           && p('0:id,name,parent') && e('dGFnJTJGUkVBRE1FLm1k,README.md,dGFn'); //获取svn代码文件得提交信息第一个文件夹信息
r(count($result) > 2)               && p()                   && e('1'); //获取svn代码文件得提交信息数量