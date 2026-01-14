#!/usr/bin/env php
<?php
/**

title=productpanModel->update();
timeout=0
cid=17651

- 修改planId=1的数据,打印出旧的名称
 - 第0条的old属性 @计划1
 - 第0条的new属性 @测试修改
- 二次修改旧的数据，由于数据发生变化，理应失败第0条的old属性 @0
- 测试子计划开始日期不能小于父计划的开始日期属性begin @父计划的开始日期：2021-03-01，开始日期不能小于父计划的开始日期
- 测试子计划完成日期不能大于父计划的完成日期属性end @父计划的结束日期：2021-06-15，结束日期不能大于父计划的结束日期
- 测试完成日期不能小于开始日期第end条的0属性 @『结束日期』应当不小于『2022-03-01』。
- 测试计划名称不能为空第title条的0属性 @『计划名称』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('product')->loadYaml('product')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);

$planIdList = array(1, 2);

$postData = new stdclass();
$postData->title   = '测试修改';
$postData->status  = 'doing';
$postData->begin   = '2021-03-01';
$postData->end     = '2021-06-15';
$postData->product = 1;
$postData->parent  = 0;

$beginData = clone $postData;
$beginData->parent = 1;
$beginData->begin  = '2020-01-01';

$endData = clone $postData;
$endData->parent = 1;
$endData->end    = '2021-10-10';

$parentErrorData = clone $postData;
$parentErrorData->begin = '2022-03-01';

$titleErrorData = clone $postData;
$titleErrorData->title = '';

$planTester = new productPlan('admin');
r($planTester->updateTest($planIdList[0], $postData))        && p('0:old,new') && e('计划1,测试修改');                                                 // 修改planId=1的数据,打印出旧的名称
r($planTester->updateTest($planIdList[0], $postData))        && p('0:old')     && e('0');                                                              // 二次修改旧的数据，由于数据发生变化，理应失败
r($planTester->updateTest($planIdList[1], $beginData))       && p('begin')     && e('父计划的开始日期：2021-03-01，开始日期不能小于父计划的开始日期'); // 测试子计划开始日期不能小于父计划的开始日期
r($planTester->updateTest($planIdList[1], $endData))         && p('end')       && e('父计划的结束日期：2021-06-15，结束日期不能大于父计划的结束日期'); // 测试子计划完成日期不能大于父计划的完成日期
r($planTester->updateTest($planIdList[0], $parentErrorData)) && p('end:0')     && e('『结束日期』应当不小于『2022-03-01』。');                         // 测试完成日期不能小于开始日期
r($planTester->updateTest($planIdList[0], $titleErrorData))  && p('title:0')   && e('『计划名称』不能为空。');                                         // 测试计划名称不能为空
