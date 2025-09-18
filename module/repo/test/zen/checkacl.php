#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkACL();
timeout=0
cid=0

- 步骤1：正常开放模式属性acl @open
- 步骤2：自定义模式且都有值属性acl @custom
- 步骤3：自定义模式都为空 @0
- 步骤4：自定义模式只有groups属性acl @custom
- 步骤5：自定义模式只有users属性acl @custom

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($repoTest->checkACLTest('open', array(), array())) && p('acl') && e('open'); // 步骤1：正常开放模式
r($repoTest->checkACLTest('custom', array('1'), array('admin'))) && p('acl') && e('custom'); // 步骤2：自定义模式且都有值
r($repoTest->checkACLTest('custom', array(), array())) && p() && e('0'); // 步骤3：自定义模式都为空
r($repoTest->checkACLTest('custom', array('1'), array())) && p('acl') && e('custom'); // 步骤4：自定义模式只有groups
r($repoTest->checkACLTest('custom', array(), array('admin'))) && p('acl') && e('custom'); // 步骤5：自定义模式只有users