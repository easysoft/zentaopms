#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(1);
su('admin');

/**

title=reportModel->getProjectExecutions();
timeout=0
cid=18165

- 获取执行ID为101的名称属性101 @敏捷项目1/迭代5
- 获取执行ID为102的名称属性102 @敏捷项目1/迭代6
- 获取执行ID为103的名称属性103 @敏捷项目1/迭代7
- 获取执行ID为104的名称属性104 @敏捷项目1
- 获取执行ID为106的名称属性106 @瀑布项目2/阶段10

*/

$report = new reportModelTest();
$names  = $report->getProjectExecutionsTest();

r($names) && p('101') && e('敏捷项目1/迭代5');  //获取执行ID为101的名称
r($names) && p('102') && e('敏捷项目1/迭代6');  //获取执行ID为102的名称
r($names) && p('103') && e('敏捷项目1/迭代7');  //获取执行ID为103的名称
r($names) && p('104') && e('敏捷项目1');        //获取执行ID为104的名称
r($names) && p('106') && e('瀑布项目2/阶段10'); //获取执行ID为106的名称
