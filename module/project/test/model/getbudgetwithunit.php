#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=测试 projectModel->getBudgetWithUnit();
timeout=0
cid=1
pid=1

- 执行project模块的getBudgetWithUnit方法，参数是222 @222

- 执行project模块的getBudgetWithUnit方法，参数是222.111123 @222.11

- 执行project模块的getBudgetWithUnit方法，参数是222.116 @222.12

- 执行project模块的getBudgetWithUnit方法，参数是12222 @1.22万

- 执行project模块的getBudgetWithUnit方法，参数是12345.126 @1.23万

- 执行project模块的getBudgetWithUnit方法，参数是120000000 @1.2亿

- 执行project模块的getBudgetWithUnit方法，参数是120000000.1233 @1.2亿

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getBudgetWithUnit(222))            && p() && e('222');
r($tester->project->getBudgetWithUnit(222.111123))     && p() && e('222.11');
r($tester->project->getBudgetWithUnit(222.116))        && p() && e('222.12');
r($tester->project->getBudgetWithUnit(12222))          && p() && e('1.22万');
r($tester->project->getBudgetWithUnit(12345.126))      && p() && e('1.23万');
r($tester->project->getBudgetWithUnit(120000000))      && p() && e('1.2亿');
r($tester->project->getBudgetWithUnit(120000000.1233)) && p() && e('1.2亿');
