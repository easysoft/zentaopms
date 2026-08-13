#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getTreeByGraphql();
timeout=0
cid=18081

- 获取 gitlab 类型版本库1的 master 分支文件夹列表第0条的name属性 @public
- 获取 gitlab 类型版本库1的 master 分支文件列表第2条的name属性 @sonar-project.properties
- 获取 gitlab 类型版本库1的 master 分支 public 路径下文件夹数量 @0
- 获取 gitlab 类型版本库1的 master 分支 public 路径下文件列表第0条的name属性 @index.html
- 获取 gitlab 类型版本库1的 branch1 分支文件夹列表第0条的name属性 @public
- 获取 gitlab 类型版本库1的 branch1 分支文件列表第1条的name属性 @README.md

*/

$repo = new repoModelTest();

r($repo->getTreeByGraphqlTest(1, '', 'master', 'trees'))   && p('0:name') && e('public');
r($repo->getTreeByGraphqlTest(1, '', 'master', 'blobs'))   && p('2:name') && e('sonar-project.properties');
r($repo->getTreeByGraphqlCountTest(1, 'public', 'master', 'trees')) && p() && e('0');
r($repo->getTreeByGraphqlTest(1, 'public', 'master', 'blobs')) && p('0:name') && e('index.html');
r($repo->getTreeByGraphqlTest(1, '', 'branch1', 'trees'))  && p('0:name') && e('public');
r($repo->getTreeByGraphqlTest(1, '', 'branch1', 'blobs'))  && p('1:name') && e('README.md');
