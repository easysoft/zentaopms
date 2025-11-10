#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::computeFieldsCreateView();
timeout=0
cid=0

- 步骤1：测试ipd项目模型stage执行类型无planID,检查requiredFields包含enabled,point,type,name,begin,end
 - 属性1 @
- 步骤2：测试ipd项目模型stage执行类型带planID,检查requiredFields包含type但不包含enabled
 - 属性1 @
- 步骤3：测试waterfallplus项目模型stage执行类型,检查requiredFields包含type
 - 属性1 @
- 步骤4：测试waterfall项目模型stage执行类型,检查requiredFields包含name,begin,end
 - 属性1 @
- 步骤5：测试sprint执行类型,检查visibleFields包含PM,milestone,acl,desc,attribute @,PM,milestone,acl,desc,attribute

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project');
zenData('product');
zenData('user');

su('admin');

$programplanTest = new programplanTest();

// 准备测试数据 - ipd项目模型,stage执行类型,无planID
$viewData1 = new stdclass();
$viewData1->project = new stdclass();
$viewData1->project->model = 'ipd';
$viewData1->executionType = 'stage';
$viewData1->planID = 0;

// 准备测试数据 - ipd项目模型,stage执行类型,带planID
$viewData2 = new stdclass();
$viewData2->project = new stdclass();
$viewData2->project->model = 'ipd';
$viewData2->executionType = 'stage';
$viewData2->planID = 1;

// 准备测试数据 - waterfallplus项目模型,stage执行类型
$viewData3 = new stdclass();
$viewData3->project = new stdclass();
$viewData3->project->model = 'waterfallplus';
$viewData3->executionType = 'stage';
$viewData3->planID = 0;

// 准备测试数据 - waterfall项目模型,stage执行类型
$viewData4 = new stdclass();
$viewData4->project = new stdclass();
$viewData4->project->model = 'waterfall';
$viewData4->executionType = 'stage';
$viewData4->planID = 0;

// 准备测试数据 - sprint执行类型(customAgilePlus)
$viewData5 = new stdclass();
$viewData5->project = new stdclass();
$viewData5->project->model = 'waterfall';
$viewData5->executionType = 'sprint';
$viewData5->planID = 0;

r($programplanTest->computeFieldsCreateViewTest($viewData1)) && p('1') && e(',enabled,point,type,name,begin,end'); // 步骤1：测试ipd项目模型stage执行类型无planID,检查requiredFields包含enabled,point,type,name,begin,end
r($programplanTest->computeFieldsCreateViewTest($viewData2)) && p('1') && e(',type,name,begin,end'); // 步骤2：测试ipd项目模型stage执行类型带planID,检查requiredFields包含type但不包含enabled
r($programplanTest->computeFieldsCreateViewTest($viewData3)) && p('1') && e(',type,name,begin,end'); // 步骤3：测试waterfallplus项目模型stage执行类型,检查requiredFields包含type
r($programplanTest->computeFieldsCreateViewTest($viewData4)) && p('1') && e(',name,begin,end'); // 步骤4：测试waterfall项目模型stage执行类型,检查requiredFields包含name,begin,end
r($programplanTest->computeFieldsCreateViewTest($viewData5)) && p('0') && e(',PM,milestone,acl,desc,attribute'); // 步骤5：测试sprint执行类型,检查visibleFields包含PM,milestone,acl,desc,attribute