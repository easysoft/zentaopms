#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::computeFieldsCreateView();
timeout=0
cid=0

- 执行$result1['error']) ? 1 : 0 @1
- 执行$result2['error']) ? 1 : 0 @0
- 执行$result3['error']) ? 1 : 0 @0
- 执行$result4['error']) ? 1 : 0 @0
- 执行$result5['error']) ? 1 : 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project');
zenData('product');
zenData('user');

su('admin');

$programplanTest = new programplanTest();

// 测试步骤1：测试stage执行类型的字段计算
$viewData1 = new stdclass();
$viewData1->project = new stdclass();
$viewData1->project->model = 'scrum';
$viewData1->executionType = 'stage';
$viewData1->planID = 0;
$result1 = $programplanTest->computeFieldsCreateViewTest($viewData1);
r(isset($result1['error']) ? 1 : 0) && p() && e(1);

// 测试步骤2：测试sprint执行类型的字段计算
$viewData2 = new stdclass();
$viewData2->project = new stdclass();
$viewData2->project->model = 'scrum';
$viewData2->executionType = 'sprint';
$viewData2->planID = 0;
$result2 = $programplanTest->computeFieldsCreateViewTest($viewData2);
r(isset($result2['error']) ? 1 : 0) && p() && e(0);

// 测试步骤3：测试ipd模型项目的字段计算
$viewData3 = new stdclass();
$viewData3->project = new stdclass();
$viewData3->project->model = 'ipd';
$viewData3->executionType = 'stage';
$viewData3->planID = 0;
$result3 = $programplanTest->computeFieldsCreateViewTest($viewData3);
r(isset($result3['error']) ? 1 : 0) && p() && e(0);

// 测试步骤4：测试waterfallplus模型项目的字段计算
$viewData4 = new stdclass();
$viewData4->project = new stdclass();
$viewData4->project->model = 'waterfallplus';
$viewData4->executionType = 'stage';
$viewData4->planID = 0;
$result4 = $programplanTest->computeFieldsCreateViewTest($viewData4);
r(isset($result4['error']) ? 1 : 0) && p() && e(0);

// 测试步骤5：测试带有planID的情况字段计算
$viewData5 = new stdclass();
$viewData5->project = new stdclass();
$viewData5->project->model = 'scrum';
$viewData5->executionType = 'stage';
$viewData5->planID = 1;
$result5 = $programplanTest->computeFieldsCreateViewTest($viewData5);
r(isset($result5['error']) ? 1 : 0) && p() && e(1);