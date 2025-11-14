#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->loadYaml('product')->gen(100);
zenData('project')->loadYaml('project')->gen(100);
zenData('project')->loadYaml('execution')->gen(300, false);
zenData('build')->gen(500);
zenData('testrun')->gen(70);
zenData('testresult')->gen(50);
zenData('testtask')->loadYaml('testtask')->gen(500);

/**

title=测试 testtaskModel->getProductUnitTasks();
timeout=0
cid=19184

- 查询产品ID为1的单元测试单的数量 @25
- 查询产品ID为1的筛选条件为全部的单元测试单的数量 @25
- 查询产品ID为1的筛选条件为最近的单元测试单的数量 @25
- 查询产品ID为1的筛选条件为本周的单元测试单的数量 @25
- 查询产品ID为1的筛选条件为上周的单元测试单的数量 @0
- 查询产品ID为1的筛选条件为本月的单元测试单的数量 @25
- 查询产品ID为1的筛选条件为上月的单元测试单的数量 @0
- 验证按ID正序排序后的第一条数据是否正确属性name @单元测试41
- 验证按ID倒序排序后的第一条数据是否正确属性name @单元测试1
- 验证按所属项目正序排序后的第一条数据是否正确属性name @单元测试41
- 验证按所属项目倒序排序后的第一条数据是否正确属性name @单元测试1
- 查询产品ID为1、测试单ID为51的测试单对应的产品名称第51条的productName属性 @正常产品1
- 查询产品ID为2、测试单ID为52的测试单对应的产品名称第52条的productName属性 @项目12
- 查询产品ID为1、测试单ID为51的测试单对应的执行名称第51条的executionName属性 @迭代1
- 查询产品ID为1、测试单ID为52的测试单对应的执行名称第52条的executionName属性 @项目12/迭代2
- 查询产品ID为1、测试单ID为51的测试单对应的执行用例数、通过数、失败数
 - 第51条的caseCount属性 @3
 - 第51条的passCount属性 @2
 - 第51条的failCount属性 @1
- 验证产品ID为1，索引为351数据的所有字段
 - 第351条的id属性 @351
 - 第351条的project属性 @11
 - 第351条的name属性 @单元测试1
 - 第351条的product属性 @1
 - 第351条的execution属性 @101
 - 第351条的build属性 @11
 - 第351条的owner属性 @user5
 - 第351条的pri属性 @3
 - 第351条的desc属性 @这是测试单描述351
 - 第351条的status属性 @done
 - 第351条的testreport属性 @0
 - 第351条的auto属性 @unit
 - 第351条的deleted属性 @0

*/

global $tester;
$tester->loadModel('testtask');

r(count($tester->testtask->getProductUnitTasks(1)))              && p() && e('25'); // 查询产品ID为1的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'all')))       && p() && e('25'); // 查询产品ID为1的筛选条件为全部的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'newest')))    && p() && e('25'); // 查询产品ID为1的筛选条件为最近的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'thisWeek')))  && p() && e('25'); // 查询产品ID为1的筛选条件为本周的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'lastWeek')))  && p() && e('0');  // 查询产品ID为1的筛选条件为上周的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'thisMonth'))) && p() && e('25'); // 查询产品ID为1的筛选条件为本月的单元测试单的数量
r(count($tester->testtask->getProductUnitTasks(1, 'lastMonth'))) && p() && e('0');  // 查询产品ID为1的筛选条件为上月的单元测试单的数量

$data = $tester->testtask->getProductUnitTasks(1, 'all', 'id_desc');
r(reset($data)) && p('name') && e('单元测试41'); // 验证按ID正序排序后的第一条数据是否正确

$data = $tester->testtask->getProductUnitTasks(1, 'all', 'id_asc');
r(reset($data)) && p('name') && e('单元测试1'); // 验证按ID倒序排序后的第一条数据是否正确

$data = $tester->testtask->getProductUnitTasks(1, 'all', 'project_desc');
r(reset($data)) && p('name') && e('单元测试41'); // 验证按所属项目正序排序后的第一条数据是否正确

$data = $tester->testtask->getProductUnitTasks(1, 'all', 'project_asc');
r(reset($data)) && p('name') && e('单元测试1'); // 验证按所属项目倒序排序后的第一条数据是否正确

r($tester->testtask->getProductUnitTasks(1, 'all')) && p('51:productName')   && e('正常产品1');    // 查询产品ID为1、测试单ID为51的测试单对应的产品名称
r($tester->testtask->getProductUnitTasks(2, 'all')) && p('52:productName')   && e('项目12');       // 查询产品ID为2、测试单ID为52的测试单对应的产品名称
r($tester->testtask->getProductUnitTasks(1, 'all')) && p('51:executionName') && e('迭代1');        // 查询产品ID为1、测试单ID为51的测试单对应的执行名称
r($tester->testtask->getProductUnitTasks(2, 'all')) && p('52:executionName') && e('项目12/迭代2'); // 查询产品ID为1、测试单ID为52的测试单对应的执行名称

r($tester->testtask->getProductUnitTasks(1, 'all')) && p('51:caseCount,passCount,failCount')   && e('3,2,1');    // 查询产品ID为1、测试单ID为51的测试单对应的执行用例数、通过数、失败数

r($tester->testtask->getProductUnitTasks(1)) && p('351:id,project,name,product,execution,build,owner,pri,desc,status,testreport,auto,deleted') && e('351,11,单元测试1,1,101,11,user5,3,这是测试单描述351,done,0,unit,0'); // 验证产品ID为1，索引为351数据的所有字段