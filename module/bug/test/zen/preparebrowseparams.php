#!/usr/bin/env php
<?php

/**

title=测试 bugZen::prepareBrowseParams();
timeout=0
cid=0

- 执行$result[0] @1
- 执行$result[1] @0
- 执行$result[2] @id_desc
- 执行$result[0] @5
- 执行$result[1] @0
- 执行$result[0] @1
- 执行$result[1] @10
- 执行$result[2] @severity,id_desc

- 执行$result[3]->recTotal @150
- 执行$result[3]->recPerPage @30
- 执行$result[3]->pageID @5
- 执行$result[3]->recTotal @0
- 执行$result[0] @1
- 执行$result[1] @0
- 执行$result[0] @100
- 执行$result[3]->recTotal @10000
- 执行$result[3]->pageID @10

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$bugTest = new bugTest();

// 忽略警告信息
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

// 4. 执行测试步骤（至少5个）
// 步骤1：browseType为all时的正常处理
$result = $bugTest->prepareBrowseParamsTest('all', 0, 'id_desc', 100, 20, 1);
r($result[0]) && p() && e('1');
r($result[1]) && p() && e('0');
r($result[2]) && p() && e('id_desc');

// 步骤2：browseType为bymodule时设置moduleID
$result = $bugTest->prepareBrowseParamsTest('bymodule', 5, 'status', 50, 10, 2);
r($result[0]) && p() && e('5');
r($result[1]) && p() && e('0');

// 步骤3：browseType为bysearch时设置queryID
$result = $bugTest->prepareBrowseParamsTest('bysearch', 10, 'pri_asc', 200, 25, 3);
r($result[0]) && p() && e('1');
r($result[1]) && p() && e('10');

// 步骤4：验证orderBy添加id排序规则
$result = $bugTest->prepareBrowseParamsTest('all', 0, 'severity', 30, 15, 1);
r($result[2]) && p() && e('severity,id_desc');

// 步骤5：验证分页参数设置
$result = $bugTest->prepareBrowseParamsTest('all', 0, 'id', 150, 30, 5);
r($result[3]->recTotal) && p() && e('150');
r($result[3]->recPerPage) && p() && e('30');
r($result[3]->pageID) && p() && e('5');

// 步骤6：测试边界值recTotal为0
$result = $bugTest->prepareBrowseParamsTest('all', 0, 'id', 0, 20, 1);
r($result[3]->recTotal) && p() && e('0');
r($result[0]) && p() && e('1');
r($result[1]) && p() && e('0');

// 步骤7：测试大数据量分页
$result = $bugTest->prepareBrowseParamsTest('bymodule', 100, 'pri_desc', 10000, 50, 10);
r($result[0]) && p() && e('100');
r($result[3]->recTotal) && p() && e('10000');
r($result[3]->pageID) && p() && e('10');