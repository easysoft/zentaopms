#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$normalBuild, 10, 'normal' 属性hasProductField @0
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$branchBuild, 20, 'branch' 属性hasBranchField @1
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$platformBuild, 30, 'platform' 属性hasBranchField @1
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$projectBuild, 40, 'normal' 属性hasPlanField @0
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$paramBuild, 50, 'normal' 属性queryID @50

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('build')->loadYaml('zt_build_buildlinkstorysearchform', false, 2)->gen(5);
zenData('product')->loadYaml('zt_product_buildlinkstorysearchform', false, 2)->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$buildTest = new buildTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试build对象 - 普通产品
$normalBuild = new stdclass();
$normalBuild->id = 1;
$normalBuild->product = 1;
$normalBuild->branch = '0';
$normalBuild->project = 11;
$normalBuild->allStories = '1,2,3';

// 测试步骤1：普通产品类型的搜索表单配置
r($buildTest->buildLinkStorySearchFormTest($normalBuild, 10, 'normal')) && p('hasProductField') && e('0');

// 创建测试build对象 - 分支产品
$branchBuild = new stdclass();
$branchBuild->id = 2;
$branchBuild->product = 2;
$branchBuild->branch = '1,2';
$branchBuild->project = 12;
$branchBuild->allStories = '4,5,6';

// 测试步骤2：分支产品类型的搜索表单配置
r($buildTest->buildLinkStorySearchFormTest($branchBuild, 20, 'branch')) && p('hasBranchField') && e('1');

// 创建测试build对象 - 平台产品
$platformBuild = new stdclass();
$platformBuild->id = 3;
$platformBuild->product = 3;
$platformBuild->branch = '0,1';
$platformBuild->project = 13;
$platformBuild->allStories = '7,8,9';

// 测试步骤3：平台产品类型的搜索表单配置
r($buildTest->buildLinkStorySearchFormTest($platformBuild, 30, 'platform')) && p('hasBranchField') && e('1');

// 创建测试build对象 - 有项目的情况
$projectBuild = new stdclass();
$projectBuild->id = 4;
$projectBuild->product = 1;
$projectBuild->branch = '0';
$projectBuild->project = 14;
$projectBuild->allStories = '10,11,12';

// 测试步骤4：有项目且项目不支持产品的搜索表单
r($buildTest->buildLinkStorySearchFormTest($projectBuild, 40, 'normal')) && p('hasPlanField') && e('0');

// 创建测试build对象 - 验证参数设置
$paramBuild = new stdclass();
$paramBuild->id = 5;
$paramBuild->product = 1;
$paramBuild->branch = '0';
$paramBuild->project = 15;
$paramBuild->allStories = '13,14,15';

// 测试步骤5：queryID和style参数设置验证
r($buildTest->buildLinkStorySearchFormTest($paramBuild, 50, 'normal')) && p('queryID') && e('50');