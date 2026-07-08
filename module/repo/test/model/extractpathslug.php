#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->extractPathSlug();
timeout=0
cid=18124

- 测试 extractPathSlug 从完整 URL 提取路径 @group/testrepo
- 测试 extractPathSlug 从相对路径提取路径 @group/testrepo
- 测试 extractPathSlug 从普通路径提取路径 @group/testrepo
- 测试 extractPathSlug 空路径返回空 @~~
*/

$repo = new repoModelTest();

r($repo->extractPathSlugTest('https://gitlab.example.com/group/testrepo')) && p() && e('group/testrepo');
r($repo->extractPathSlugTest('/group/testrepo')) && p() && e('group/testrepo');
r($repo->extractPathSlugTest('group/testrepo')) && p() && e('group/testrepo');
r($repo->extractPathSlugTest('')) && p() && e('~~');
