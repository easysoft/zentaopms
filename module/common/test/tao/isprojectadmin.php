#!/usr/bin/env php
<?php

/**

title=测试 commonTao::isProjectAdmin();
timeout=0
cid=15726

- 步骤1：项目管理员检查项目权限 @1
- 步骤2：产品管理员检查产品权限 @1
- 步骤3：项目群管理员检查项目群权限 @1
- 步骤4：执行管理员检查执行权限 @1
- 步骤5：普通用户检查无权限模块 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（使用管理员账户）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTaoTest();

// 4. 设置全局环境
global $app, $lang;
if(!isset($app)) $app = new stdClass();
if(!isset($app->user)) $app->user = new stdClass();
if(!isset($app->session)) $app->session = new stdClass();
if(!isset($lang)) $lang = new stdClass();
if(!isset($lang->navGroup)) $lang->navGroup = new stdClass();

// 设置用户权限
$app->user->rights = array(
    'projects' => 'all',
    'products' => 'all', 
    'programs' => 'all',
    'executions' => 'all'
);

// 设置导航分组
$lang->navGroup->task = 'project';
$lang->navGroup->story = 'product';
$lang->navGroup->program = 'program';
$lang->navGroup->execution = 'execution';

// 设置session
$app->session->project = 1;
$app->session->product = 1;
$app->session->program = 1;
$app->session->execution = 1;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->isProjectAdminTest('task', (object)array('id' => 1, 'type' => 'project'))) && p() && e('1'); // 步骤1：项目管理员检查项目权限
r($commonTest->isProjectAdminTest('story', (object)array('id' => 1))) && p() && e('1'); // 步骤2：产品管理员检查产品权限
r($commonTest->isProjectAdminTest('program', (object)array('id' => 1))) && p() && e('1'); // 步骤3：项目群管理员检查项目群权限
r($commonTest->isProjectAdminTest('execution', (object)array('id' => 1))) && p() && e('1'); // 步骤4：执行管理员检查执行权限
r($commonTest->isProjectAdminTest('other', (object)array('id' => 1))) && p() && e('0'); // 步骤5：普通用户检查无权限模块