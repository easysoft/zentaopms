#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getGitlabFilesByPath();
timeout=0
cid=1

- 获取gitlab类型版本库1的master分支文件列表
 - 第0条的name属性 @public
 - 第0条的kind属性 @dir
- 获取gitlab类型版本库1的master分支文件列表数量 @1
- 获取gitlab类型版本库1的master分支public路径下文件列表
 - 第0条的name属性 @index.html
 - 第0条的kind属性 @file
- 获取gitlab类型版本库1的master分支public路径下文件列表数量 @1
- 获取gitlab类型版本库1的branch1分支文件列表
 - 第2条的name属性 @README.md
 - 第2条的kind属性 @file
- 获取gitlab类型版本库1的brnach1分支文件列表数量 @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repoIds = array(1);
$paths   = array('', 'public');
$branches = array('master', 'branch1');

$repo = new repoTest();

$result = $repo->getGitlabFilesByPathTest($repoIds[0], $paths[0], $branches[0]);
r($result)            && p('0:name,kind') && e('public,dir'); // 获取gitlab类型版本库1的master分支文件列表
r(count($result) > 3) && p()              && e('1');          // 获取gitlab类型版本库1的master分支文件列表数量

$result = $repo->getGitlabFilesByPathTest($repoIds[0], $paths[1], $branches[0]);
r($result)            && p('0:name,kind') && e('index.html,file'); // 获取gitlab类型版本库1的master分支public路径下文件列表
r(count($result) > 1) && p()              && e('1');          // 获取gitlab类型版本库1的master分支public路径下文件列表数量

$result = $repo->getGitlabFilesByPathTest($repoIds[0], $paths[0], $branches[1]);
r($result)            && p('2:name,kind') && e('README.md,file'); // 获取gitlab类型版本库1的branch1分支文件列表
r(count($result) > 2) && p()              && e('1');          // 获取gitlab类型版本库1的brnach1分支文件列表数量
