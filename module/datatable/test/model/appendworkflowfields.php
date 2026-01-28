#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::appendWorkflowFields();
timeout=0
cid=15940

- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'user', 'browse'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'build', 'build'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'task', 'task'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'bug', 'bug'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'story', 'story'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'testcase', 'testcase'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'projectrelease', 'browse'  @0
- 执行datatableTest模块的appendWorkflowFieldsTest方法，参数是'product', ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

$datatableTest = new datatableModelTest();

// 在开源版本中，appendWorkflowFields应该返回空数组，因为只有企业版才有工作流功能
r($datatableTest->appendWorkflowFieldsTest('user', 'browse')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('build', 'build')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('task', 'task')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('bug', 'bug')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('story', 'story')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('testcase', 'testcase')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('projectrelease', 'browse')) && p() && e('0');
r($datatableTest->appendWorkflowFieldsTest('product', '')) && p() && e('0');