#!/usr/bin/env php
<?php

/**

title=测试 mrZen::getAllProjects();
timeout=0
cid=17267

- 执行mrTest模块的getAllProjectsTest方法，参数是$repo1  @array
- 执行mrTest模块的getAllProjectsTest方法，参数是$repo2  @array
- 执行mrTest模块的getAllProjectsTest方法，参数是$repo3  @array
- 执行mrTest模块的getAllProjectsTest方法，参数是$repo4  @array
- 执行mrTest模块的getAllProjectsTest方法，参数是$repo5  @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('view');

zendata('repo')->loadYaml('getallprojects/repo', false, 2)->gen(10);

su('admin');

$mrTest = new mrZenTest();

// 准备测试数据:gitlab类型的repo对象
$repo1 = new stdclass();
$repo1->id = 1;
$repo1->SCM = 'gitlab';
$repo1->serviceHost = 1;
$repo1->serviceProject = 'gitlab/project1';

// 准备测试数据:gitea类型的repo对象
$repo2 = new stdclass();
$repo2->id = 2;
$repo2->SCM = 'gitea';
$repo2->serviceHost = 2;
$repo2->serviceProject = 'gitea/project2';

// 准备测试数据:gogs类型的repo对象
$repo3 = new stdclass();
$repo3->id = 3;
$repo3->SCM = 'gogs';
$repo3->serviceHost = 3;
$repo3->serviceProject = 'gogs/project3';

// 准备测试数据:gitlab类型的repo对象,不同的项目
$repo4 = new stdclass();
$repo4->id = 4;
$repo4->SCM = 'gitlab';
$repo4->serviceHost = 4;
$repo4->serviceProject = 'gitlab/project4';

// 准备测试数据:gitea类型的repo对象,不同的项目
$repo5 = new stdclass();
$repo5->id = 5;
$repo5->SCM = 'gitea';
$repo5->serviceHost = 5;
$repo5->serviceProject = 'gitea/project5';

r($mrTest->getAllProjectsTest($repo1)) && p() && e('array');
r($mrTest->getAllProjectsTest($repo2)) && p() && e('array');
r($mrTest->getAllProjectsTest($repo3)) && p() && e('array');
r($mrTest->getAllProjectsTest($repo4)) && p() && e('array');
r($mrTest->getAllProjectsTest($repo5)) && p() && e('array');