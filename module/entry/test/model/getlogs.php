#!/usr/bin/env php
<?php

/**

title=测试 entryModel::getLogs();
timeout=0
cid=16250

- 步骤1：正常情况-获取ID为1的entry日志列表数量 @6
- 步骤2：边界值-获取不存在ID的entry日志列表数量 @5
- 步骤3：排序测试-验证日志对象类型和ID
 - 第0条的objectType属性 @entry
 - 第0条的0:objectID属性 @1
- 步骤4：排序测试-验证正序排列
 - 第0条的objectType属性 @entry
 - 第0条的0:objectID属性 @2
- 步骤5：内容验证-检查日志URL和内容类型
 - 第0条的url属性 @/entry/view/1
 - 第0条的0:contentType属性 @html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('log');
$table->id->range('1-15');
$table->objectType->range('entry{10},task{3},bug{2}');
$table->objectID->range('1{6},2{2},3{2},999{5}');
$table->action->range('1-50:R');
$table->date->range('(-7D)-(-1D):1D');
$table->url->range('/entry/view/1,/entry/view/2,/entry/edit/1,/entry/logs/1,/entry/create');
$table->contentType->range('html{8},json{4},xml{3}');
$table->data->range('{"test":"data1"},{"test":"data2"},[]{3}');
$table->result->range('success{10},error{3},warning{2}');
$table->gen(15);

zenData('user')->gen(5);

su('admin');

$entryTest = new entryModelTest();

r(count($entryTest->getLogsTest(1))) && p() && e('6'); // 步骤1：正常情况-获取ID为1的entry日志列表数量
r(count($entryTest->getLogsTest(999))) && p() && e('5'); // 步骤2：边界值-获取不存在ID的entry日志列表数量
r($entryTest->getLogsTest(1, 'date_desc')) && p('0:objectType,0:objectID') && e('entry,1'); // 步骤3：排序测试-验证日志对象类型和ID
r($entryTest->getLogsTest(2, 'date_asc')) && p('0:objectType,0:objectID') && e('entry,2'); // 步骤4：排序测试-验证正序排列
r($entryTest->getLogsTest(1)) && p('0:url,0:contentType') && e('/entry/view/1,html'); // 步骤5：内容验证-检查日志URL和内容类型