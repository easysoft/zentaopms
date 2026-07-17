#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::getAllProjects();
timeout=0
cid=0

- 执行ppmZen模块的getAllProjectsTest方法，参数是$repo1), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0  @1
- 执行ppmZen模块的getAllProjectsTest方法，参数是$repo2), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0  @1
- 执行ppmZen模块的getAllProjectsTest方法，参数是$repo3), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0  @1
- 执行ppmZen模块的getAllProjectsTest方法，参数是$repo4, 'pullreq'), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0  @1
- 执行ppmZen模块的getAllProjectsTest方法，参数是$repo5), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->rawModule = 'ppm';
$app->rawMethod = 'view';
$app->setMethodName('view');

su('admin');

$ppmZen = new ppmZenTest();
$repo1  = (object)array('serviceHost' => 1, 'serviceProject' => 'group/project-a');
$repo2  = (object)array('serviceHost' => 2, 'serviceProject' => 'group/project-b');
$repo3  = (object)array('serviceHost' => 1, 'serviceProject' => 'group/project-c');
$repo4  = (object)array('serviceHost' => 3, 'serviceProject' => 'group/project-d');
$repo5  = (object)array('serviceHost' => 4, 'serviceProject' => '');

r(strpos((string)$ppmZen->getAllProjectsTest($repo1), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0) && p() && e('1');
r(strpos((string)$ppmZen->getAllProjectsTest($repo2), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0) && p() && e('1');
r(strpos((string)$ppmZen->getAllProjectsTest($repo3), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0) && p() && e('1');
r(strpos((string)$ppmZen->getAllProjectsTest($repo4, 'pullreq'), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0) && p() && e('1');
r(strpos((string)$ppmZen->getAllProjectsTest($repo5), 'the module ppm has no getGitFoxProjects method') !== false ? 1 : 0) && p() && e('1');