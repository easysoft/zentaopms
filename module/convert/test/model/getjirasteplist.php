#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraStepList();
timeout=0
cid=15783

- 步骤1：空参数测试属性object @对象映射
- 步骤2：正常数据测试属性1 @Story对象数据映射
- 步骤3：带issueTypeList测试属性1 @Bug对象数据映射
- 步骤4：add_custom跳过测试属性2 @Story对象数据映射
- 步骤5：空对象ID测试属性1 @Task对象数据映射

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 设置测试环境变量
global $app;
$app->session->set('jiraMethod', 'file');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getJiraStepListTest(array(), array())) && p('object') && e('对象映射'); // 步骤1：空参数测试
r($convertTest->getJiraStepListTest(array('jiraObject' => array(1, 2), 'zentaoObject' => array(1 => 'story', 2 => 'task')), array(1 => (object)array('pname' => 'Story'), 2 => (object)array('pname' => 'Task')))) && p('1') && e('Story对象数据映射'); // 步骤2：正常数据测试
r($convertTest->getJiraStepListTest(array('jiraObject' => array(1), 'zentaoObject' => array(1 => 'bug')), array(1 => (object)array('pname' => 'Bug')))) && p('1') && e('Bug对象数据映射'); // 步骤3：带issueTypeList测试
r($convertTest->getJiraStepListTest(array('jiraObject' => array(1, 2), 'zentaoObject' => array(1 => 'add_custom', 2 => 'story')), array(1 => (object)array('pname' => 'Custom'), 2 => (object)array('pname' => 'Story')))) && p('2') && e('Story对象数据映射'); // 步骤4：add_custom跳过测试  
r($convertTest->getJiraStepListTest(array('jiraObject' => array(0, 1), 'zentaoObject' => array(0 => 'story', 1 => 'task')), array(1 => (object)array('pname' => 'Task')))) && p('1') && e('Task对象数据映射'); // 步骤5：空对象ID测试