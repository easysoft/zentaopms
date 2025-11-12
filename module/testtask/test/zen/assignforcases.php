#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::assignForCases();
timeout=0
cid=0

- 执行testtaskTest模块的assignForCasesTest方法，参数是$product1, $testtask1, array  @0
- 执行testtaskTest模块的assignForCasesTest方法，参数是$product2, $testtask2, array  @0
- 执行testtaskTest模块的assignForCasesTest方法，参数是$product3, $testtask3, array  @0
- 执行testtaskTest模块的assignForCasesTest方法，参数是$product1, $testtask1, array  @0
- 执行testtaskTest模块的assignForCasesTest方法，参数是$product1, $testtask1, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zenData('product')->loadYaml('testtask_assignforcases', false, 2)->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目A,项目B,项目C,项目D,项目E');
$projectTable->type->range('project{5}');
$projectTable->acl->range('open{3},private{2}');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('11-15');
$executionTable->name->range('执行A,执行B,执行C,执行D,执行E');
$executionTable->type->range('sprint{5}');
$executionTable->project->range('1-5');
$executionTable->acl->range('open{3},private{2}');
$executionTable->gen(5);

$testcaseTable = zenData('case');
$testcaseTable->id->range('1-10');
$testcaseTable->product->range('1-5');
$testcaseTable->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$testcaseTable->gen(10);

$testsuiteTable = zenData('testsuite');
$testsuiteTable->id->range('1-3');
$testsuiteTable->product->range('1-3');
$testsuiteTable->name->range('测试套件1,测试套件2,测试套件3');
$testsuiteTable->gen(3);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,qa,tester,dev1,dev2,pm,po,guest');
$userTable->realname->range('管理员,用户1,用户2,质量保证,测试员,开发1,开发2,项目经理,产品经理,访客');
$userTable->gen(10);

$teamTable = zenData('team');
$teamTable->id->range('1-5');
$teamTable->root->range('14-15');
$teamTable->type->range('execution{5}');
$teamTable->account->range('admin,user1,user2,qa,tester');
$teamTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-5');
$moduleTable->root->range('1-5');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5');
$moduleTable->type->range('case{5}');
$moduleTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-3');
$branchTable->product->range('3{3}');
$branchTable->name->range('分支1,分支2,分支3');
$branchTable->gen(3);

su('admin');

$testtaskTest = new testtaskZenTest();

$product1 = new stdclass();
$product1->id = 1;
$product1->name = '产品A';
$product1->shadow = 0;
$product1->type = 'normal';

$testtask1 = new stdclass();
$testtask1->id = 1;
$testtask1->name = '测试单A';
$testtask1->execution = 11;
$testtask1->branch = '0';
$testtask1->product = 1;

$run1 = new stdclass();
$run1->id = 1;
$run1->case = 1;
$run1->task = 1;
$run1->lastRunner = 'admin';

$pager1 = new stdclass();
$pager1->recTotal = 1;
$pager1->pageTotal = 1;
$pager1->pageID = 1;

r($testtaskTest->assignForCasesTest($product1, $testtask1, array($run1), array(), 0, 'all', 0, 'id_desc', $pager1)) && p() && e('0');

$product2 = new stdclass();
$product2->id = 2;
$product2->name = '产品B';
$product2->shadow = 0;
$product2->type = 'normal';

$testtask2 = new stdclass();
$testtask2->id = 2;
$testtask2->name = '测试单B';
$testtask2->execution = 14;
$testtask2->branch = '0';
$testtask2->product = 2;

r($testtaskTest->assignForCasesTest($product2, $testtask2, array($run1), array(), 0, 'all', 0, 'id_desc', $pager1)) && p() && e('0');

$product3 = new stdclass();
$product3->id = 3;
$product3->name = '产品C';
$product3->shadow = 0;
$product3->type = 'branch';

$testtask3 = new stdclass();
$testtask3->id = 3;
$testtask3->name = '测试单C';
$testtask3->execution = 13;
$testtask3->branch = '1';
$testtask3->product = 3;

r($testtaskTest->assignForCasesTest($product3, $testtask3, array($run1), array(), 0, 'all', 0, 'id_desc', $pager1)) && p() && e('0');

r($testtaskTest->assignForCasesTest($product1, $testtask1, array($run1), array(), 0, 'bysuite', 1, 'id_desc', $pager1)) && p() && e('0');

r($testtaskTest->assignForCasesTest($product1, $testtask1, array(), array(), 0, 'all', 0, 'id_desc', $pager1)) && p() && e('0');