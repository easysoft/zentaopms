#!/usr/bin/env php
<?php

/**

title=测试 screenModel::checkIFChartInUse();
timeout=0
cid=18222

- 步骤1：检查chart类型图表被使用 @1
- 步骤2：检查pivot类型图表被使用 @1
- 步骤3：检查metric类型图表被使用 @1
- 步骤4：检查分组中的chart类型图表被使用 @1
- 步骤5：检查不存在的图表ID @0
- 步骤6：检查无效的图表ID（0） @0
- 步骤7：检查错误的type参数（正确的是chart） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

global $tester;
$tester->dbh->exec("TRUNCATE zt_screen");
$tester->dbh->exec("INSERT INTO zt_screen (id, name, scheme, status, deleted) VALUES 
(1, 'Test Screen 1', '{\"componentList\":[{\"chartConfig\":{\"sourceID\":\"101\",\"package\":\"Charts\"},\"isGroup\":false}]}', 'published', '0'),
(2, 'Test Screen 2', '{\"componentList\":[{\"chartConfig\":{\"sourceID\":\"102\",\"package\":\"Tables\"},\"isGroup\":false}]}', 'published', '0'),
(3, 'Test Screen 3', '{\"componentList\":[{\"chartConfig\":{\"sourceID\":\"103\",\"package\":\"Metrics\"},\"isGroup\":false}]}', 'published', '0'),
(4, 'Group Screen', '{\"componentList\":[{\"isGroup\":true,\"groupList\":[{\"chartConfig\":{\"sourceID\":\"104\",\"package\":\"Charts\"}}]}]}', 'published', '0'),
(5, 'Empty Screen', '{\"componentList\":[]}', 'published', '0')");

su('admin');

$screenTest = new screenTest();

r($screenTest->checkIFChartInUseTest('101', 'chart')) && p() && e('1');          // 步骤1：检查chart类型图表被使用
r($screenTest->checkIFChartInUseTest('102', 'pivot')) && p() && e('1');          // 步骤2：检查pivot类型图表被使用
r($screenTest->checkIFChartInUseTest('103', 'metric')) && p() && e('1');         // 步骤3：检查metric类型图表被使用
r($screenTest->checkIFChartInUseTest('104', 'chart')) && p() && e('1');          // 步骤4：检查分组中的chart类型图表被使用
r($screenTest->checkIFChartInUseTest('999', 'chart')) && p() && e('0');          // 步骤5：检查不存在的图表ID
r($screenTest->checkIFChartInUseTest('0', 'chart')) && p() && e('0');            // 步骤6：检查无效的图表ID（0）
r($screenTest->checkIFChartInUseTest('101', 'pivot')) && p() && e('0');          // 步骤7：检查错误的type参数（正确的是chart）