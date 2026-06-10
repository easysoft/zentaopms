#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfResolvedBugsPerDay();
timeout=0
cid=15378

- 步骤1：验证第一天的日期格式第0条的name属性 @2025-09-17
- 步骤2：验证第一天的bug数量第0条的value属性 @3
- 步骤3：验证第二天的日期格式第1条的name属性 @2025-09-18
- 步骤4：验证第二天的bug数量第1条的value属性 @2
- 步骤5：验证第三天的完整数据
 - 第2条的name属性 @2025-09-19
 - 第2条的value属性 @1

*/

// 1. 导入依赖 - 初始化测试框架和bug测试类
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备 - 使用稳定的日期分布避免串跑污染
zenData('bug')->gen(0);
zenData('bug')->gen(6);
global $tester;
$tester->dao->update(TABLE_BUG)->set('status')->eq('resolved')->where('id')->in('1,2,3,4,5,6')->exec();
$tester->dao->update(TABLE_BUG)->set('resolvedDate')->eq('2025-09-17 10:00:00')->where('id')->in('1,2,3')->exec();
$tester->dao->update(TABLE_BUG)->set('resolvedDate')->eq('2025-09-18 11:00:00')->where('id')->in('4,5')->exec();
$tester->dao->update(TABLE_BUG)->set('resolvedDate')->eq('2025-09-19 12:00:00')->where('id')->eq('6')->exec();
unset($_SESSION['bugQueryCondition'], $_SESSION['bugOnlyCondition']);

// 3. 用户登录 - 使用管理员身份进行测试
su('admin');

// 4. 创建测试实例 - 实例化bug模块的单元测试类
$bugTest = new bugModelTest();

// 5. 测试步骤执行 - 覆盖多种测试场景，确保方法的健壮性和准确性
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:name') && e('2025-09-17'); // 步骤1：验证第一天的日期格式
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('3'); // 步骤2：验证第一天的bug数量
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('1:name') && e('2025-09-18'); // 步骤3：验证第二天的日期格式
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('1:value') && e('2'); // 步骤4：验证第二天的bug数量
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('2:name,value') && e('2025-09-19,1'); // 步骤5：验证第三天的完整数据
