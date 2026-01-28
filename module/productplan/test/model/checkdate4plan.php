#!/usr/bin/env php
<?php
/**

title=productpanModel->checkDate4Plan();
timeout=0
cid=17625

- 父计划输入正确的开始日期，检查通过 @测试通过
- 父计划输入正确的结束日期，检查通过 @测试通过
- 父计划输入错误的开始日期，检查报错提示信息属性begin @子计划的开始日期：2021-06-01，开始日期不能大于子计划的开始日期
- 父计划输入错误的结束日期，检查报错提示信息属性end @子计划的结束日期：2021-06-15，结束日期不能小于子计划的结束日期
- 子计划输入正确的开始日期，检查通过 @测试通过
- 子计划输入正确的结束日期，检查通过 @测试通过
- 子计划输入错误的开始日期，检查报错提示信息属性begin @父计划的开始日期：2021-01-01，开始日期不能小于父计划的开始日期
- 子计划输入错误的结束日期，检查报错提示信息属性end @父计划的结束日期：2021-06-30，结束日期不能大于父计划的结束日期

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('user')->gen(5);

$planIdList = array(1, 2, 3);
$beginList  = array('2030-01-01', '2021-06-02', '2020-01-01');
$endList    = array('2030-01-01', '2021-06-14', '2021-07-30');

$planTester = new productplan('admin');
r($planTester->checkDate4PlanTest($planIdList[0], $beginList[0], $endList[0])) && p('0')     && e('测试通过');                                                       // 父计划输入正确的开始日期，检查通过
r($planTester->checkDate4PlanTest($planIdList[0], $beginList[0], $endList[0])) && p('0')     && e('测试通过');                                                       // 父计划输入正确的结束日期，检查通过
r($planTester->checkDate4PlanTest($planIdList[0], $beginList[1], $endList[0])) && p('begin') && e('子计划的开始日期：2021-06-01，开始日期不能大于子计划的开始日期'); // 父计划输入错误的开始日期，检查报错提示信息
r($planTester->checkDate4PlanTest($planIdList[0], $beginList[0], $endList[1])) && p('end')   && e('子计划的结束日期：2021-06-15，结束日期不能小于子计划的结束日期'); // 父计划输入错误的结束日期，检查报错提示信息
r($planTester->checkDate4PlanTest($planIdList[1], $beginList[1], $endList[1])) && p('0')     && e('测试通过');                                                       // 子计划输入正确的开始日期，检查通过
r($planTester->checkDate4PlanTest($planIdList[1], $beginList[1], $endList[1])) && p('0')     && e('测试通过');                                                       // 子计划输入正确的结束日期，检查通过
r($planTester->checkDate4PlanTest($planIdList[1], $beginList[2], $endList[1])) && p('begin') && e('父计划的开始日期：2021-01-01，开始日期不能小于父计划的开始日期'); // 子计划输入错误的开始日期，检查报错提示信息
r($planTester->checkDate4PlanTest($planIdList[1], $beginList[1], $endList[2])) && p('end')   && e('父计划的结束日期：2021-06-30，结束日期不能大于父计划的结束日期'); // 子计划输入错误的结束日期，检查报错提示信息
