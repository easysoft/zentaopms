#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getWorkspaceInfo();
timeout=0
cid=15684

- 步骤1：测试已缓存的工作区信息，验证enabled属性 @1
- 步骤2：测试禁用工作区配置，验证enabled属性 @~~
- 步骤3：测试空工作区列表场景，验证enabled属性 @1
- 步骤4：测试cookie与当前tab不匹配场景，验证opened属性 @~~
- 步骤5：测试cookie与当前tab匹配场景，验证opened属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 设置应用程序上下文
global $app, $config, $lang;
if(empty($app->control))
{
    $app->control = $tester;
}
if(empty($app->user))
{
    $app->user = (object)array('account' => 'admin', 'rights' => array());
}

// 确保必要的属性存在
if(!isset($app->moduleName)) $app->moduleName = 'common';
if(!isset($app->methodName)) $app->methodName = 'index';
if(!isset($app->rawModule))  $app->rawModule  = 'common';
if(!isset($app->rawMethod))  $app->rawMethod  = 'index';

// 4. 创建测试实例
$commonTest = new commonModelTest();

// 5. 测试步骤

// 步骤1：测试已缓存的工作区信息
if(isset($app->workspaceInfo)) unset($app->workspaceInfo);
$app->workspaceInfo = array('enabled' => true, 'type' => 'test', 'opened' => true);
r($commonTest->getWorkspaceInfoTest()) && p('enabled') && e('1');

// 步骤2：测试禁用工作区配置
unset($app->workspaceInfo);
$config->noWorkspace = true;
r($commonTest->getWorkspaceInfoTest()) && p('enabled') && e('~~');
unset($config->noWorkspace);

// 步骤3：测试空工作区列表场景
unset($app->workspaceInfo);
$app->tab = 'product';
$app->lang->workspaceList = array();
r($commonTest->getWorkspaceInfoTest()) && p('enabled') && e('1');

// 步骤4：测试cookie与当前tab不匹配场景
unset($app->workspaceInfo);
$app->tab = 'product';
$app->lang->workspaceList = array('product' => 'Product Workspace');
$app->cookie->workspace = 'project';
// 设置homeMenu为空，确保setMainMenu返回false
if(!isset($lang->product)) $lang->product = new stdClass();
$lang->product->homeMenu = null;
r($commonTest->getWorkspaceInfoTest()) && p('opened') && e('~~');

// 步骤5：测试cookie与当前tab匹配场景
unset($app->workspaceInfo);
$app->tab = 'product';
$app->lang->workspaceList = array('product' => 'Product Workspace');
$app->cookie->workspace = 'product';
// 设置homeMenu为空，确保setMainMenu返回false
if(!isset($lang->product)) $lang->product = new stdClass();
$lang->product->homeMenu = null;
r($commonTest->getWorkspaceInfoTest()) && p('opened') && e('1');
