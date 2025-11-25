#!/usr/bin/env php
<?php

/**

title=测试 groupZen::appendWorkflowMenu();
timeout=0
cid=16730

- 步骤1：browse方法有menus @1
- 步骤2：browse方法无menus @1
- 步骤3：非browse方法 @1
- 步骤4：权限配置结构 @1
- 步骤5：依赖关系 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

su('admin');

$groupTest = new groupZenTest();

// 准备测试环境：初始化全局语言配置
global $lang, $config;
if(!isset($lang->testworkflow)) $lang->testworkflow = new stdclass();
if(!isset($lang->testworkflow->menus))
{
    $lang->testworkflow->menus = new stdclass();
    $lang->testworkflow->menus->all = '全部';
    $lang->testworkflow->menus->open = '未关闭';
    $lang->testworkflow->menus->finished = '已完成';
}

if(!isset($lang->testmodule)) $lang->testmodule = new stdclass();

// 初始化配置包结构
if(!isset($config->group->package)) $config->group->package = new stdclass();

// 初始化测试包结构
$config->group->package->testworkflowbrowse = new stdclass();
$result1 = $groupTest->appendWorkflowMenuTest('testworkflowbrowse', 'testworkflow', 'browse');
r(isset($result1['testworkflow-browse']) ? 1 : 0) && p() && e('1'); // 步骤1：browse方法有menus

$config->group->package->testmodulebrowse = new stdclass();
$result2 = $groupTest->appendWorkflowMenuTest('testmodulebrowse', 'testmodule', 'browse');
r(isset($result2['testmodule-browse']) ? 1 : 0) && p() && e('1'); // 步骤2：browse方法无menus  

$config->group->package->testworkflowcreate = new stdclass();
$result3 = $groupTest->appendWorkflowMenuTest('testworkflowcreate', 'testworkflow', 'create');
r(isset($result3['testworkflow-create']) ? 1 : 0) && p() && e('1'); // 步骤3：非browse方法

$config->group->package->testpackage = new stdclass();
$result4 = $groupTest->appendWorkflowMenuTest('testpackage', 'testworkflow', 'browse');
r(isset($result4['testworkflow-browse']['edition']) ? 1 : 0) && p() && e('1'); // 步骤4：权限配置结构

$config->group->package->testdependency = new stdclass();
$result5 = $groupTest->appendWorkflowMenuTest('testdependency', 'testworkflow', 'browse');
r(isset($result5['testworkflow-all']['depend'][0]) && $result5['testworkflow-all']['depend'][0] == 'testworkflow-browse' ? 1 : 0) && p() && e('1'); // 步骤5：依赖关系