#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkDepends();
timeout=0
cid=0

- 步骤1：没有依赖时应返回true @1
- 步骤2：满足依赖版本时应返回true @1
- 步骤3：不满足依赖最小版本时应返回false @0
- 步骤4：不满足依赖最大版本时应返回false @0
- 步骤5：缺少依赖插件时应返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 创建测试数据 - 没有依赖的条件
$noDependsCondition = new stdClass();
$noDependsCondition->depends = null;

// 创建测试数据 - 有依赖的条件
$hasDepends = new stdClass();
$hasDepends->depends = array(
    'plugin1' => array('min' => '1.0.0', 'max' => '2.0.0'),
    'plugin2' => array('min' => '1.5.0'),
    'plugin3' => 'all'
);

// 创建测试数据 - 不满足最小版本依赖的条件
$minVersionDepends = new stdClass();
$minVersionDepends->depends = array(
    'plugin1' => array('min' => '2.0.0')
);

// 创建测试数据 - 不满足最大版本依赖的条件
$maxVersionDepends = new stdClass();
$maxVersionDepends->depends = array(
    'plugin1' => array('max' => '1.0.0')
);

// 创建测试数据 - 缺少依赖插件的条件
$missingDepends = new stdClass();
$missingDepends->depends = array(
    'nonexistent' => array('min' => '1.0.0')
);

// 已安装的插件列表
$installedExts = array(
    'plugin1' => (object)array('version' => '1.5.0', 'name' => 'Plugin 1'),
    'plugin2' => (object)array('version' => '1.8.0', 'name' => 'Plugin 2'),
    'plugin3' => (object)array('version' => '1.0.0', 'name' => 'Plugin 3')
);

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->checkDependsTest($noDependsCondition, $installedExts)) && p() && e('1');               // 步骤1：没有依赖时应返回true
r($extensionTest->checkDependsTest($hasDepends, $installedExts)) && p() && e('1');                        // 步骤2：满足依赖版本时应返回true  
r($extensionTest->checkDependsTest($minVersionDepends, $installedExts)) && p() && e('0');                // 步骤3：不满足依赖最小版本时应返回false
r($extensionTest->checkDependsTest($maxVersionDepends, $installedExts)) && p() && e('0');                // 步骤4：不满足依赖最大版本时应返回false
r($extensionTest->checkDependsTest($missingDepends, $installedExts)) && p() && e('0');                   // 步骤5：缺少依赖插件时应返回false