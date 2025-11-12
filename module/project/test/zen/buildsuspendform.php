#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildSuspendForm();
timeout=0
cid=0

- 执行projectTest模块的buildSuspendFormTest方法，参数是1 属性title @挂起项目
- 执行projectTest模块的buildSuspendFormTest方法 属性title @挂起项目
- 执行projectTest模块的buildSuspendFormTest方法，参数是999 属性title @挂起项目
- 执行projectTest模块的buildSuspendFormTest方法，参数是-1 属性title @挂起项目
- 执行projectTest模块的buildSuspendFormTest方法，参数是null 属性title @挂起项目

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

zenData('project')->loadYaml('project_buildsuspendform', false, 2)->gen(10);
zenData('user')->loadYaml('user_buildsuspendform', false, 2)->gen(15);
zenData('action')->loadYaml('action_buildsuspendform', false, 2)->gen(20);

su('admin');

$projectTest = new projectzenTest();

r($projectTest->buildSuspendFormTest(1)) && p('title') && e('挂起项目');
r($projectTest->buildSuspendFormTest(0)) && p('title') && e('挂起项目');
r($projectTest->buildSuspendFormTest(999)) && p('title') && e('挂起项目');
r($projectTest->buildSuspendFormTest(-1)) && p('title') && e('挂起项目');
r($projectTest->buildSuspendFormTest(null)) && p('title') && e('挂起项目');