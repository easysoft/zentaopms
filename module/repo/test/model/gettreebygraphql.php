#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getTreeByGraphql();
timeout=0
cid=1

- 获取gitlab类型版本库1的master分支文件夹列表第0条的name属性 @public
- 获取gitlab类型版本库1的master分支文件列表第2条的name属性 @sonar-project.properties
- 获取gitlab类型版本库1的master分支public路径下文件夹列表数量 @0
- 获取gitlab类型版本库1的master分支public路径下文件列表第0条的name属性 @index.html
- 获取gitlab类型版本库1的branch1分支文件夹列表第0条的name属性 @public
- 获取gitlab类型版本库1的branch1分支文件列表第1条的name属性 @README.md

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repoIds  = array(1);
$paths    = array('', 'public');
$branches = array('master', 'branch1');
$types    = array('trees', 'blobs');

$repo = new repoTest();

r($repo->getTreeByGraphqlTest($repoIds[0], $paths[0], $branches[0], $types[0])) && p('0:name') && e('public'); // 获取gitlab类型版本库1的master分支文件夹列表
r($repo->getTreeByGraphqlTest($repoIds[0], $paths[0], $branches[0], $types[1])) && p('2:name') && e('sonar-project.properties'); // 获取gitlab类型版本库1的master分支文件列表

$result = $repo->getTreeByGraphqlTest($repoIds[0], $paths[1], $branches[0], $types[0]);
r(count($result)) && p()              && e('0');          // 获取gitlab类型版本库1的master分支public路径下文件夹列表数量
r($repo->getTreeByGraphqlTest($repoIds[0], $paths[1], $branches[0], $types[1])) && p('0:name') && e('index.html'); // 获取gitlab类型版本库1的master分支public路径下文件列表

r($repo->getTreeByGraphqlTest($repoIds[0], $paths[0], $branches[1], $types[0])) && p('0:name') && e('public'); // 获取gitlab类型版本库1的branch1分支文件夹列表
r($repo->getTreeByGraphqlTest($repoIds[0], $paths[0], $branches[1], $types[1])) && p('1:name') && e('README.md'); // 获取gitlab类型版本库1的branch1分支文件列表