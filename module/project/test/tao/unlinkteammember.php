#!/usr/bin/env php
<?php

/**

title=测试 projectTao::unlinkTeamMember();
timeout=0
cid=17920

- 步骤1：删除单个项目的团队成员 @1
- 步骤2：删除多个项目的团队成员 @1
- 步骤3：删除不存在的团队成员 @1
- 步骤4：删除execution类型的团队成员 @1
- 步骤5：传入空的项目ID列表 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
// 简化数据准备，不生成测试数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->unlinkTeamMemberTest(1, 'project', 'user1', '用户1', array())) && p() && e(1); // 步骤1：删除单个项目的团队成员
r($projectTest->unlinkTeamMemberTest(array(2, 3), 'project', 'user2', '用户2', array())) && p() && e(1); // 步骤2：删除多个项目的团队成员
r($projectTest->unlinkTeamMemberTest(4, 'project', 'notexist', '不存在用户', array())) && p() && e(1); // 步骤3：删除不存在的团队成员
r($projectTest->unlinkTeamMemberTest(5, 'execution', 'user3', '用户3', array())) && p() && e(1); // 步骤4：删除execution类型的团队成员
r($projectTest->unlinkTeamMemberTest(array(), 'project', 'user4', '用户4', array())) && p() && e(1); // 步骤5：传入空的项目ID列表