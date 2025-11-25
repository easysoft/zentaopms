#!/usr/bin/env php
<?php

/**

title=测试 groupZen::appendResourcePackages();
timeout=0
cid=16729

- 步骤1：正常情况 @array
- 步骤2：边界值 @1
- 步骤3：异常输入 @1
- 步骤4：权限验证 @1
- 步骤5：业务规则 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';
su('admin');

$groupZenTest = new groupZenTest();

// 设置测试环境：模拟一些缺失的权限资源
global $lang;
if(!isset($lang->resource)) $lang->resource = new stdclass();
if(!isset($lang->resource->testmodule)) 
{
    $lang->resource->testmodule = new stdclass();
    $lang->resource->testmodule->create = 'create';
    $lang->resource->testmodule->browse = 'browse';
    $lang->resource->testmodule->edit = 'edit';
    $lang->resource->testmodule->delete = 'delete';
    $lang->resource->testmodule->view = 'view';
}

r(gettype($groupZenTest->appendResourcePackagesTest())) && p() && e('array'); // 步骤1：正常情况

$result = $groupZenTest->appendResourcePackagesTest();
r(isset($result['subsets']) ? 1 : 0) && p() && e('1'); // 步骤2：边界值

r(isset($result['packages']) ? 1 : 0) && p() && e('1'); // 步骤3：异常输入

r(count((array)$result['subsets']) > 0 ? 1 : 0) && p() && e('1'); // 步骤4：权限验证

r(count((array)$result['packages']) > 0 ? 1 : 0) && p() && e('1'); // 步骤5：业务规则