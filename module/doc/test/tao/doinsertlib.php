#!/usr/bin/env php
<?php

/**

title=测试 docTao::doInsertLib();
timeout=0
cid=16166

- 执行docTest模块的doInsertLibTest方法，参数是$validLib  @6
- 执行docTest模块的doInsertLibTest方法，参数是$validLib2  @7
- 执行docTest模块的doInsertLibTest方法，参数是$productLib  @8
- 执行docTest模块的doInsertLibTest方法，参数是$projectLib  @9
- 执行docTest模块的doInsertLibTest方法，参数是$specialCharLib  @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zendata('doclib')->loadYaml('doclib_doinsertlib', false, 2)->gen(5);

su('admin');

$docTest = new docTest();

// 测试步骤1：正常插入有效的文档库数据
$validLib = new stdClass();
$validLib->type = 'custom';
$validLib->name = '测试文档库1';
$validLib->vision = 'rnd';
$validLib->acl = 'open';
$validLib->product = 0;
$validLib->project = 0;
$validLib->execution = 0;
$validLib->order = 1;
$validLib->deleted = '0';
r($docTest->doInsertLibTest($validLib)) && p() && e('6');

// 测试步骤2：正常插入另一个有效的文档库数据
$validLib2 = new stdClass();
$validLib2->type = 'custom';
$validLib2->name = '测试文档库2';
$validLib2->vision = 'rnd';
$validLib2->acl = 'private';
$validLib2->product = 0;
$validLib2->project = 0;
$validLib2->execution = 0;
$validLib2->order = 2;
$validLib2->deleted = '0';
r($docTest->doInsertLibTest($validLib2)) && p() && e('7');

// 测试步骤3：正常插入产品类型的文档库
$productLib = new stdClass();
$productLib->type = 'product';
$productLib->name = '产品文档库';
$productLib->vision = 'rnd';
$productLib->acl = 'open';
$productLib->product = 1;
$productLib->project = 0;
$productLib->execution = 0;
$productLib->order = 3;
$productLib->deleted = '0';
r($docTest->doInsertLibTest($productLib)) && p() && e('8');

// 测试步骤4：正常插入项目类型的文档库
$projectLib = new stdClass();
$projectLib->type = 'project';
$projectLib->name = '项目文档库';
$projectLib->vision = 'rnd';
$projectLib->acl = 'open';
$projectLib->product = 0;
$projectLib->project = 1;
$projectLib->execution = 0;
$projectLib->order = 4;
$projectLib->deleted = '0';
r($docTest->doInsertLibTest($projectLib)) && p() && e('9');

// 测试步骤5：正常插入包含特殊字符的文档库名称
$specialCharLib = new stdClass();
$specialCharLib->type = 'custom';
$specialCharLib->name = '测试文档库-API&DOC';
$specialCharLib->vision = 'rnd';
$specialCharLib->acl = 'open';
$specialCharLib->product = 0;
$specialCharLib->project = 0;
$specialCharLib->execution = 0;
$specialCharLib->order = 5;
$specialCharLib->deleted = '0';
r($docTest->doInsertLibTest($specialCharLib)) && p() && e('10');