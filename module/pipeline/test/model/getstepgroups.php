#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getStepGroups();
timeout=0
cid=0

- 调用getStepGroups接口返回构建分组属性0:groupName @build
- 调用getStepGroups接口返回构建分组属性0:desc @构建
- 调用getStepGroups接口返回SCM分组属性1:groupName @scm
- 调用getStepGroups接口返回SCM分组属性1:desc @代码版本管理
- 调用getStepGroups接口返回分组数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$tester = new pipelineModelTest();

r($tester->getStepGroupsTest()) && p('0:groupName') && e('build');
r($tester->getStepGroupsTest()) && p('0:desc') && e('构建');
r($tester->getStepGroupsTest()) && p('1:groupName') && e('scm');
r($tester->getStepGroupsTest()) && p('1:desc') && e('代码版本管理');
r(count($tester->getStepGroupsTest())) && p() && e('2');
