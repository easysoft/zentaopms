#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->buildTree();
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

$files = array(array('id' => 'TElDRU5TRQ--', 'parent' => '0', 'name' => 'LICENSE', 'path' => 'LICENSE', 'key' => 'TElDRU5TRQ=='), array('id' => 'UkVBRE1FLm1k', 'parent' => '0', 'name' => 'README.md', 'path' => 'README.md', 'key' => 'UkVBRE1FLm1k'));
$files2 = array(array('id' => 'UkVBRE1F', 'parent' => '0', 'name' => 'README', 'path' => 'README', 'key' => 'UkVBRE1F'), array('id' => 'UkVBRE1FLm1k', 'parent' => '0', 'name' => 'README.md', 'path' => 'README.md', 'key' => 'UkVBRE1FLm1k'), array('id' => 'dGFn', 'parent' => '0', 'name' => 'tag', 'path' => 'tag', 'key' => 'dGFn'), array('id' => 'dGFnJTJGUkVBRE1FLm1k', 'parent' => 'dGFn', 'name' => 'README.md', 'path' => 'tag/README.md', 'key' => 'dGFnJTJGUkVBRE1FLm1k'));

$repo = new repoTest();
$result = $repo->buildTreeTest($files);
r($result)            && p('0:parent,name,path') && e('0,LICENSE,LICENSE'); //获取代码文件得提交信息第一个文件
r(count($result) > 1) && p()                     && e('1'); //获取代码文件得提交信息数量

$result = $repo->buildTreeTest($files2);
r($result)                          && p('0:id,name,parent') && e('dGFn,tag,0'); //获取svn代码文件得提交信息第一个文件夹信息
r($result[0]['children'])           && p('0:id,name,parent') && e('dGFnJTJGUkVBRE1FLm1k,README.md,dGFn'); //获取svn代码文件得提交信息第一个文件夹信息
r(count($result) > 2)               && p()                   && e('1'); //获取svn代码文件得提交信息数量