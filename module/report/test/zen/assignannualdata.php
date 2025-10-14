#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualData();
timeout=0
cid=0

- 步骤1：正常情况-指定用户账号
 - 属性hasLogins @yes
 - 属性accountValid @yes
 - 属性success @yes
- 步骤2：边界值-指定部门无账号
 - 属性hasUsers @yes
 - 属性accountsValid @yes
 - 属性success @yes
- 步骤3：异常输入-空部门和账号
 - 属性hasStatusStat @yes
 - 属性allTimeStatus @yes
 - 属性success @yes
- 步骤4：权限验证-空账号数组
 - 属性deptEmptyAccounts @yes
 - 属性hasActions @yes
 - 属性success @yes
- 步骤5：业务规则-部门为0且有账号
 - 属性hasContributions @yes
 - 属性userCountValid @yes
 - 属性success @yes

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('action')->loadYaml('action', true, 1)->gen(10);
zenData('user')->loadYaml('user', true, 1)->gen(5);
zenData('todo')->loadYaml('todo', true, 1)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->assignAnnualDataTest('2024', '1', 'admin', array('admin'), 5)) && p('hasLogins,accountValid,success') && e('yes,yes,yes'); // 步骤1：正常情况-指定用户账号
r($reportTest->assignAnnualDataTest('2024', '1', '', array('admin', 'user1'), 5)) && p('hasUsers,accountsValid,success') && e('yes,yes,yes'); // 步骤2：边界值-指定部门无账号
r($reportTest->assignAnnualDataTest('2024', '', '', array('admin', 'user1'), 10)) && p('hasStatusStat,allTimeStatus,success') && e('yes,yes,yes'); // 步骤3：异常输入-空部门和账号
r($reportTest->assignAnnualDataTest('2024', '1', '', array(), 0)) && p('deptEmptyAccounts,hasActions,success') && e('yes,yes,yes'); // 步骤4：权限验证-空账号数组
r($reportTest->assignAnnualDataTest('2024', 0, '', array('admin'), 5)) && p('hasContributions,userCountValid,success') && e('yes,yes,yes'); // 步骤5：业务规则-部门为0且有账号