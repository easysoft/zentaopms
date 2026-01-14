#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getRecordFields();
timeout=0
cid=0

- 步骤1：dateType为nodate的度量 @system
- 步骤2：dateType为year的度量
 -  @product
 - 属性1 @year
- 步骤3：dateType为month的度量
 -  @project
 - 属性1 @year
 - 属性2 @month
- 步骤4：dateType为week的度量
 -  @execution
 - 属性1 @year
 - 属性2 @week
- 步骤5：dateType为day的度量
 -  @user
 - 属性1 @year
 - 属性2 @month
 - 属性3 @day

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('metric');
$table->code->range('metric_nodate,metric_year,metric_month,metric_week,metric_day');
$table->scope->range('system,product,project,execution,user');
$table->dateType->range('nodate,year,month,week,day');
$table->gen(5);

su('admin');

$metricTest = new metricModelTest();

r($metricTest->getRecordFieldsTest('metric_nodate')) && p('0') && e('system'); // 步骤1：dateType为nodate的度量
r($metricTest->getRecordFieldsTest('metric_year')) && p('0,1') && e('product,year'); // 步骤2：dateType为year的度量
r($metricTest->getRecordFieldsTest('metric_month')) && p('0,1,2') && e('project,year,month'); // 步骤3：dateType为month的度量
r($metricTest->getRecordFieldsTest('metric_week')) && p('0,1,2') && e('execution,year,week'); // 步骤4：dateType为week的度量
r($metricTest->getRecordFieldsTest('metric_day')) && p('0,1,2,3') && e('user,year,month,day'); // 步骤5：dateType为day的度量