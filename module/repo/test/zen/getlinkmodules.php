#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkModules();
timeout=0
cid=0

- 执行repoZenTest模块的getLinkModulesTest方法，参数是$validProducts, 'story'  @7
- 执行repoZenTest模块的getLinkModulesTest方法，参数是$validProducts, 'task'  @7
- 执行repoZenTest模块的getLinkModulesTest方法，参数是$validProducts, 'bug'  @13
- 执行repoZenTest模块的getLinkModulesTest方法，参数是$emptyProducts, 'story'  @0
- 执行repoZenTest模块的getLinkModulesTest方法，参数是$validProducts, ''  @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('module')->loadYaml('module_getlinkmodules', false, 2)->gen(20);

zendata('product')->gen(5);

su('admin');

$repoZenTest = new repoZenTest();

$product1 = new stdclass();
$product1->id = 1;
$product1->name = '产品一';

$product2 = new stdclass();
$product2->id = 2;
$product2->name = '产品二';

$product3 = new stdclass();
$product3->id = 3;
$product3->name = '产品三';

$validProducts = array(1 => $product1, 2 => $product2, 3 => $product3);
$emptyProducts = array();
$invalidProducts = array('invalid' => 'data');

r(count($repoZenTest->getLinkModulesTest($validProducts, 'story'))) && p() && e('7');
r(count($repoZenTest->getLinkModulesTest($validProducts, 'task'))) && p() && e('7');
r(count($repoZenTest->getLinkModulesTest($validProducts, 'bug'))) && p() && e('13');
r(count($repoZenTest->getLinkModulesTest($emptyProducts, 'story'))) && p() && e('0');
r(count($repoZenTest->getLinkModulesTest($validProducts, ''))) && p() && e('7');