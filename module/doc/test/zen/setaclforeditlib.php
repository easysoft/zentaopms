#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForEditLib();
timeout=0
cid=0

- 步骤1:测试custom类型文档库权限设置属性hasDefault @0
- 步骤2:测试api类型文档库(product)权限设置属性hasApiLang @1
- 步骤3:测试api类型文档库(project)权限设置属性hasApiLang @1
- 步骤4:测试mine类型文档库权限设置属性isMySpaceAclList @1
- 步骤5:测试product类型文档库权限设置属性hasOpen @0
- 步骤6:测试project类型文档库权限设置属性hasOpen @0
- 步骤7:测试execution类型文档库权限设置属性hasOpen @0
- 步骤8:测试main库的product类型
 - 属性hasPrivate @0
 - 属性hasOpen @0
- 步骤9:测试main库的mine类型属性isMySpaceAclList @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

$customLib = new stdClass();
$customLib->type = 'custom';
$customLib->main = '0';
r($docTest->setAclForEditLibTest($customLib)) && p('hasDefault') && e('0'); // 步骤1:测试custom类型文档库权限设置

$apiProductLib = new stdClass();
$apiProductLib->type = 'api';
$apiProductLib->product = 1;
$apiProductLib->project = 0;
$apiProductLib->main = '0';
r($docTest->setAclForEditLibTest($apiProductLib)) && p('hasApiLang') && e('1'); // 步骤2:测试api类型文档库(product)权限设置

$apiProjectLib = new stdClass();
$apiProjectLib->type = 'api';
$apiProjectLib->product = 0;
$apiProjectLib->project = 1;
$apiProjectLib->main = '0';
r($docTest->setAclForEditLibTest($apiProjectLib)) && p('hasApiLang') && e('1'); // 步骤3:测试api类型文档库(project)权限设置

$mineLib = new stdClass();
$mineLib->type = 'mine';
$mineLib->main = '0';
r($docTest->setAclForEditLibTest($mineLib)) && p('isMySpaceAclList') && e('1'); // 步骤4:测试mine类型文档库权限设置

$productLib = new stdClass();
$productLib->type = 'product';
$productLib->main = '0';
r($docTest->setAclForEditLibTest($productLib)) && p('hasOpen') && e('0'); // 步骤5:测试product类型文档库权限设置

$projectLib = new stdClass();
$projectLib->type = 'project';
$projectLib->main = '0';
r($docTest->setAclForEditLibTest($projectLib)) && p('hasOpen') && e('0'); // 步骤6:测试project类型文档库权限设置

$executionLib = new stdClass();
$executionLib->type = 'execution';
$executionLib->main = '0';
r($docTest->setAclForEditLibTest($executionLib)) && p('hasOpen') && e('0'); // 步骤7:测试execution类型文档库权限设置

$mainProductLib = new stdClass();
$mainProductLib->type = 'product';
$mainProductLib->main = '1';
r($docTest->setAclForEditLibTest($mainProductLib)) && p('hasPrivate;hasOpen') && e('0;0'); // 步骤8:测试main库的product类型

$mainMineLib = new stdClass();
$mainMineLib->type = 'mine';
$mainMineLib->main = '1';
r($docTest->setAclForEditLibTest($mainMineLib)) && p('isMySpaceAclList') && e('1'); // 步骤9:测试main库的mine类型