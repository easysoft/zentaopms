#!/usr/bin/env php
<?php

/**

title=测试 mrZen::getAllProjects();
timeout=0
cid=0

- 执行mrTest模块的getAllProjectsTest方法，参数是$gitlabRepo  @1
- 执行mrTest模块的getAllProjectsTest方法，参数是$giteaRepo  @1
- 执行mrTest模块的getAllProjectsTest方法，参数是$gogsRepo  @1
- 执行mrTest模块的getAllProjectsTest方法，参数是$invalidRepo  @1
- 执行mrTest模块的getAllProjectsTest方法，参数是$emptyRepo  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

zendata('repo')->gen(10);

$mrTest = new mrTest();

$gitlabRepo = new stdclass();
$gitlabRepo->id = 1;
$gitlabRepo->SCM = 'gitlab';
$gitlabRepo->serviceHost = 1;
$gitlabRepo->serviceProject = 'test-project';

$giteaRepo = new stdclass();
$giteaRepo->id = 2;
$giteaRepo->SCM = 'gitea';
$giteaRepo->serviceHost = 1;
$giteaRepo->serviceProject = 'test-project';

$gogsRepo = new stdclass();
$gogsRepo->id = 3;
$gogsRepo->SCM = 'gogs';
$gogsRepo->serviceHost = 1;
$gogsRepo->serviceProject = 'test-project';

$invalidRepo = new stdclass();
$invalidRepo->id = 4;
$invalidRepo->SCM = 'invalid';
$invalidRepo->serviceHost = 1;
$invalidRepo->serviceProject = 'test-project';

$emptyRepo = new stdclass();
$emptyRepo->SCM = '';
$emptyRepo->serviceHost = 0;
$emptyRepo->serviceProject = '';

r(is_array($mrTest->getAllProjectsTest($gitlabRepo))) && p() && e('1');
r(is_array($mrTest->getAllProjectsTest($giteaRepo))) && p() && e('1');
r(is_array($mrTest->getAllProjectsTest($gogsRepo))) && p() && e('1');
r(is_array($mrTest->getAllProjectsTest($invalidRepo))) && p() && e('1');
r(is_array($mrTest->getAllProjectsTest($emptyRepo))) && p() && e('1');