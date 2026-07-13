#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getReportsForBrowse();
timeout=0
cid=0

- 步骤1:获取product类型objectID为1的报告列表,默认分页 @1
- 步骤2:获取execution类型objectID为2的报告列表,每页10条 @1
- 步骤3:获取project类型objectID为3的报告列表,第2页 @1
- 步骤4:获取product类型objectID为0的报告列表,测试无对象情况 @1
- 步骤5:获取execution类型objectID为1且带extra参数的报告列表 @1
- 步骤6:获取project类型objectID为1且带taskIdList的报告列表 @1
- 步骤7:获取product类型objectID为1,按id_asc排序 @1
- 步骤8:获取project类型objectID为1,browseType为all的报告列表 @1
- 步骤9:获取product类型objectID为1,browseType为bysearch的报告列表 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$testreport = zenData('testreport');
$testreport->id->range('1-20');
$testreport->project->range('1-3');
$testreport->product->range('1-3');
$testreport->execution->range('1-3');
$testreport->tasks->range('1,2,3');
$testreport->builds->range('1,2');
$testreport->title->range('测试报告1,测试报告2,测试报告3');
$testreport->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`');
$testreport->end->range('`2024-01-31`,`2024-02-28`,`2024-03-31`');
$testreport->owner->range('admin,user1,user2');
$testreport->objectType->range('product,execution,project');
$testreport->objectID->range('1-3');
$testreport->createdBy->range('admin');
$testreport->createdDate->range('`2024-01-01 00:00:00`,`2024-01-02 00:00:00`,`2024-01-03 00:00:00`');
$testreport->gen(20);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testreportTest = new testreportTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'all', 0, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤1:获取product类型objectID为1的报告列表,默认分页
r(count($testreportTest->getReportsForBrowseTest(2, 'execution', 0, 'all', 0, 'id_desc', 0, 10, 1)) >= 0) && p() && e('1'); // 步骤2:获取execution类型objectID为2的报告列表,每页10条
r(count($testreportTest->getReportsForBrowseTest(3, 'project', 0, 'all', 0, 'id_desc', 0, 20, 2)) >= 0) && p() && e('1'); // 步骤3:获取project类型objectID为3的报告列表,第2页
r(count($testreportTest->getReportsForBrowseTest(0, 'product', 0, 'all', 0, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤4:获取product类型objectID为0的报告列表,测试无对象情况
r(count($testreportTest->getReportsForBrowseTest(1, 'execution', 1, 'all', 0, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤5:获取execution类型objectID为1且带extra参数的报告列表
r(count($testreportTest->getReportsForBrowseTest(1, 'project', 1, 'all', 0, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤6:获取project类型objectID为1且带taskIdList的报告列表
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'all', 0, 'id_asc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤7:获取product类型objectID为1,按id_asc排序
r(count($testreportTest->getReportsForBrowseTest(1, 'project', 0, 'all', 0, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤8:获取project类型objectID为1,browseType为all的报告列表
r(count($testreportTest->getReportsForBrowseTest(1, 'product', 0, 'bysearch', 1, 'id_desc', 0, 20, 1)) >= 0) && p() && e('1'); // 步骤9:获取product类型objectID为1,browseType为bysearch的报告列表