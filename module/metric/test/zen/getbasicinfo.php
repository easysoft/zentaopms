#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getBasicInfo();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性scope @system
 - 属性object @user
 - 属性purpose @scale
 - 属性dateType @day
 - 属性name @测试度量1
- 步骤2：指定字段
 - 属性name @测试度量2
 - 属性code @metric_code_2
 - 属性unit @hour
- 步骤3：基本字段验证
 - 属性scope @product
 - 属性object @task
 - 属性purpose @scale
- 步骤4：空字段参数 @0
- 步骤5：包含扩展字段
 - 属性name @测试度量7
 - 属性code @metric_code_7
 - 属性scope @system

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->id->range('1-10');
$table->name->range('测试度量1,测试度量2,测试度量3,测试度量4,测试度量5,测试度量6,测试度量7,测试度量8,测试度量9,测试度量10');
$table->alias->range('test_metric_1,test_metric_2,test_metric_3,test_metric_4,test_metric_5,test_metric_6,test_metric_7,test_metric_8,test_metric_9,test_metric_10');
$table->code->range('metric_code_1,metric_code_2,metric_code_3,metric_code_4,metric_code_5,metric_code_6,metric_code_7,metric_code_8,metric_code_9,metric_code_10');
$table->scope->range('system,system,product,product,project,project,system,system,product,product');
$table->object->range('user,user,task,task,bug,bug,user,user,task,task');
$table->purpose->range('scale,quality,scale,quality,scale,quality,scale,quality,scale,quality');
$table->dateType->range('day,week,month,day,week,month,day,week,month,day');
$table->unit->range('count,hour,percent,count,hour,percent,count,hour,percent,count');
$table->stage->range('wait,released,wait,released,wait,released,wait,released,wait,released');
$table->type->range('php,sql,php,sql,php,sql,php,sql,php,sql');
$table->builtin->range('0,1,0,1,0,1,0,1,0,1');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getBasicInfoTest(1)) && p('scope,object,purpose,dateType,name') && e('system,user,scale,day,测试度量1'); // 步骤1：正常情况
r($metricTest->getBasicInfoTest(2, 'name,code,unit')) && p('name,code,unit') && e('测试度量2,metric_code_2,hour'); // 步骤2：指定字段
r($metricTest->getBasicInfoTest(3)) && p('scope,object,purpose') && e('product,task,scale'); // 步骤3：基本字段验证
r($metricTest->getBasicInfoTest(4, '')) && p() && e('0'); // 步骤4：空字段参数
r($metricTest->getBasicInfoTest(7, 'name,code,scope')) && p('name,code,scope') && e('测试度量7,metric_code_7,system'); // 步骤5：包含扩展字段