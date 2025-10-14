#!/usr/bin/env php
<?php

/**

title=测试 bugZen::mergeChartOption();
timeout=0
cid=0

- 步骤1：正常情况，默认类型
 - 属性type @pie
 - 属性width @500
 - 属性height @140
- 步骤2：指定图表类型为bar
 - 属性type @bar
 - 属性width @500
 - 属性height @140
- 步骤3：指定图表类型为pie，覆盖默认bar类型
 - 属性type @pie
 - 属性width @500
 - 属性height @140
- 步骤4：空类型参数，使用默认类型
 - 属性type @pie
 - 属性width @500
 - 属性height @140
- 步骤5：default类型参数
 - 属性type @pie
 - 属性width @500
 - 属性height @140

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->mergeChartOptionTest('bugsPerExecution', 'default')) && p('type,width,height') && e('pie,500,140'); // 步骤1：正常情况，默认类型
r($bugTest->mergeChartOptionTest('bugsPerBuild', 'bar')) && p('type,width,height') && e('bar,500,140'); // 步骤2：指定图表类型为bar
r($bugTest->mergeChartOptionTest('openedBugsPerDay', 'pie')) && p('type,width,height') && e('pie,500,140'); // 步骤3：指定图表类型为pie，覆盖默认bar类型
r($bugTest->mergeChartOptionTest('bugsPerSeverity', '')) && p('type,width,height') && e('pie,500,140'); // 步骤4：空类型参数，使用默认类型
r($bugTest->mergeChartOptionTest('bugsPerModule', 'default')) && p('type,width,height') && e('pie,500,140'); // 步骤5：default类型参数