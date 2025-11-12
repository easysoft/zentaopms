#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildStartForm();
timeout=0
cid=0

- 执行projectTest模块的buildStartFormTest方法，参数是1  @正常返回启动表单视图
- 执行projectTest模块的buildStartFormTest方法  @项目ID为0的处理
- 执行projectTest模块的buildStartFormTest方法，参数是2  @等待状态项目可启动
- 执行projectTest模块的buildStartFormTest方法，参数是3  @已启动状态项目
- 执行projectTest模块的buildStartFormTest方法，参数是null  @方法签名正确

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

zenData('project')->loadYaml('project', false, 2)->gen(10);
zenData('user')->loadYaml('user', false, 2)->gen(20);
zenData('action')->loadYaml('action', false, 2)->gen(50);

su('admin');

$projectTest = new projectzenTest();

r($projectTest->buildStartFormTest(1)) && p() && e('正常返回启动表单视图');
r($projectTest->buildStartFormTest(0)) && p() && e('项目ID为0的处理');
r($projectTest->buildStartFormTest(2)) && p() && e('等待状态项目可启动');
r($projectTest->buildStartFormTest(3)) && p() && e('已启动状态项目');
r($projectTest->buildStartFormTest(null)) && p() && e('方法签名正确');