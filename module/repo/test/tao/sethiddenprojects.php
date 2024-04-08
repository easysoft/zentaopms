#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

/**

title=测试 repoTao::setHiddenProjects();
timeout=0
cid=1

- 测试将代码库里的项目隐藏 @1
- 测试不覆盖已隐藏的代码库项目 @1
- 测试给admin用户添加已隐藏的代码库项目 @1
- 测试给system用户添加已隐藏的代码库项目 @1

*/

$repo = new repoTest();
r($repo->setHiddenProjectsTest(1, '1,2,3'))                 && p() && e('1'); // 测试将代码库里的项目隐藏
r($repo->setHiddenProjectsTest(1, [2,3,4], 'admin', false)) && p() && e('1'); // 测试不覆盖已隐藏的代码库项目
r($repo->setHiddenProjectsTest(1, [1,2,3], 'admin'))        && p() && e('1'); // 测试给admin用户添加已隐藏的代码库项目
r($repo->setHiddenProjectsTest(1, [1,2,3], 'system'))       && p() && e('1'); // 测试给system用户添加已隐藏的代码库项目
