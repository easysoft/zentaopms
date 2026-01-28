#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getJobBySonarqubeProject();
timeout=0
cid=16842

- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array 属性zentaopms @10
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是999, array  @0
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array  @0
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array 属性zentaopms @10
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array 属性zentaopms @10
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array 属性zentaopms @10
- 执行job模块的getJobBySonarqubeProjectTest方法，参数是2, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('job');
$table->loadYaml('job_getjobbysonarqubeproject', false, 2);
$table->gen(10);

su('admin');

$job = new jobModelTest();

// 测试步骤1：正常查询指定sonarqube服务器和项目key
r($job->getJobBySonarqubeProjectTest(2, array('zentaopms'))) && p('zentaopms') && e('10');

// 测试步骤2：查询不存在的sonarqube服务器ID
r($job->getJobBySonarqubeProjectTest(999, array('zentaopms'))) && p() && e('0');

// 测试步骤3：查询空项目key数组且emptyShowAll为false
r($job->getJobBySonarqubeProjectTest(2, array(), false)) && p() && e('0');

// 测试步骤4：查询空项目key数组且emptyShowAll为true
r($job->getJobBySonarqubeProjectTest(2, array(), true)) && p('zentaopms') && e('10');

// 测试步骤5：查询包含已删除job且showDeleted为true
r($job->getJobBySonarqubeProjectTest(2, array('zentaopms'), false, true)) && p('zentaopms') && e('10');

// 测试步骤6：查询包含已删除job且showDeleted为false
r($job->getJobBySonarqubeProjectTest(2, array('zentaopms'), false, false)) && p('zentaopms') && e('10');

// 测试步骤7：查询不存在的项目key
r($job->getJobBySonarqubeProjectTest(2, array('nonexistent'))) && p() && e('0');