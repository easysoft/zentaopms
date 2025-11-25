#!/usr/bin/env php
<?php

/**

title=测试 commonTao::checkPrivByRights();
timeout=0
cid=15723

- 步骤1：正常权限检查情况 @1
- 步骤2：无数据库权限情况 @0
- 步骤3：acls['views']为空的情况 @1
- 步骤4：特殊模块处理情况 @1
- 步骤5：产品模块特殊处理 @1
- 步骤6：系统默认返回true的情况 @1
- 步骤7：views配置中不包含对应菜单 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($commonTest->checkPrivByRightsTest('user', 'browse', array('views' => array('system' => true)), null)) && p() && e('1'); // 步骤1：正常权限检查情况
r($commonTest->checkPrivByRightsTest('user', 'edit', array('views' => array('system' => true)), 'no_db_priv')) && p() && e('0'); // 步骤2：无数据库权限情况
r($commonTest->checkPrivByRightsTest('task', 'view', array(), null)) && p() && e('1'); // 步骤3：acls['views']为空的情况
r($commonTest->checkPrivByRightsTest('my', 'team', array('views' => array('system' => true)), null)) && p() && e('1'); // 步骤4：特殊模块处理情况
r($commonTest->checkPrivByRightsTest('story', 'view', array('views' => array('product' => true)), null)) && p() && e('1'); // 步骤5：产品模块特殊处理
r($commonTest->checkPrivByRightsTest('index', 'dashboard', array('views' => array('project' => true)), null)) && p() && e('1'); // 步骤6：系统默认返回true的情况
r($commonTest->checkPrivByRightsTest('bug', 'create', array('views' => array('product' => true)), null)) && p() && e('0'); // 步骤7：views配置中不包含对应菜单