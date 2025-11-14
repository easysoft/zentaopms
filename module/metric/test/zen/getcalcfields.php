#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getCalcFields();
timeout=0
cid=17189

- 执行metricTest模块的getCalcFieldsZenTest方法，参数是$calc1, $row1 
 - 属性id @1
 - 属性name @test
 - 属性status @active
- 执行metricTest模块的getCalcFieldsZenTest方法，参数是$calc2, $row2 
 - 属性total_count @100
 - 属性total_value @5000
- 执行metricTest模块的getCalcFieldsZenTest方法，参数是$calc3, $row3 
 - 属性id @10
 - 属性name @testuser
 - 属性title @testtask
- 执行metricTest模块的getCalcFieldsZenTest方法，参数是$calc4, $row4 
 - 属性id @20
 - 属性count @50
 - 属性name @testproject
- 执行metricTest模块的getCalcFieldsZenTest方法，参数是$calc5, $row5 
 - 属性id @30
 - 属性estimate @8
 - 属性defaultHours @40

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 4. 测试步骤执行

// 步骤1：测试无dataset的calc对象，应直接返回原始row数据
$calc1 = new stdclass();
$row1 = new stdclass();
$row1->id = 1;
$row1->name = 'test';
$row1->status = 'active';
r($metricTest->getCalcFieldsZenTest($calc1, $row1)) && p('id,name,status') && e('1,test,active');

// 步骤2：测试带AS别名的字段处理
$calc2 = new stdclass();
$calc2->dataset = 'testDataset';
$calc2->fieldList = array('COUNT(*) AS total_count', 'SUM(value) AS total_value');
$row2 = new stdclass();
$row2->total_count = 100;
$row2->total_value = 5000;
r($metricTest->getCalcFieldsZenTest($calc2, $row2)) && p('total_count,total_value') && e('100,5000');

// 步骤3：测试表名.字段名格式的字段处理
$calc3 = new stdclass();
$calc3->dataset = 'testDataset';
$calc3->fieldList = array('user.id', 'user.name', 'task.title');
$row3 = new stdclass();
$row3->user_id = 10;
$row3->user_name = 'testuser';
$row3->task_title = 'testtask';
r($metricTest->getCalcFieldsZenTest($calc3, $row3)) && p('id,name,title') && e('10,testuser,testtask');

// 步骤4：测试混合格式字段处理
$calc4 = new stdclass();
$calc4->dataset = 'testDataset';
$calc4->fieldList = array('user.id', 'COUNT(*) AS count', 'project.name');
$row4 = new stdclass();
$row4->user_id = 20;
$row4->count = 50;
$row4->project_name = 'testproject';
r($metricTest->getCalcFieldsZenTest($calc4, $row4)) && p('id,count,name') && e('20,50,testproject');

// 步骤5：测试defaultHours特殊字段的保留
$calc5 = new stdclass();
$calc5->dataset = 'testDataset';
$calc5->fieldList = array('task.id', 'task.estimate');
$row5 = new stdclass();
$row5->task_id = 30;
$row5->task_estimate = 8;
$row5->defaultHours = 40;
r($metricTest->getCalcFieldsZenTest($calc5, $row5)) && p('id,estimate,defaultHours') && e('30,8,40');