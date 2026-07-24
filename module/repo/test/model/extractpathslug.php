#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->extractPathSlug();
timeout=0
cid=18124

- 测试 extractPathSlug 从完整 URL 提取域名和路径 @https://gitlab.example.com,group/testrepo
- 测试 extractPathSlug 从相对路径提取路径 @group/testrepo
- 测试 extractPathSlug 从普通路径提取路径 @group/testrepo
- 测试 extractPathSlug 包含多余空格时提取路径 @group/testrepo2
- 测试 extractPathSlug 从 svn URL 提取域名和路径 @svn://svn.example.com,svn/repo
*/

$repo = new repoModelTest();

r($repo->extractPathSlugTest('https://gitlab.example.com/group/testrepo')) && p('url,path') && e('https://gitlab.example.com,group/testrepo');
r($repo->extractPathSlugTest('/group/testrepo')) && p('path') && e('group/testrepo');
r($repo->extractPathSlugTest('group/testrepo')) && p('path') && e('group/testrepo');
r($repo->extractPathSlugTest('  /group/testrepo2  ')) && p('path') && e('group/testrepo2');
r($repo->extractPathSlugTest('svn://svn.example.com/svn/repo')) && p('url,path') && e('svn://svn.example.com,svn/repo');
