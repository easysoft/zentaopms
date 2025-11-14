#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::setSearchParamsForLinkCase();
timeout=0
cid=19244

- 执行testtaskTest模块的setSearchParamsForLinkCaseTest方法，参数是$normalProduct, $task, '', 0  @0
- 执行testtaskTest模块的setSearchParamsForLinkCaseTest方法，参数是$branchProduct, $task, 'bystory', 1  @0
- 执行testtaskTest模块的setSearchParamsForLinkCaseTest方法，参数是$shadowProduct, $task, '', 0  @0
- 执行testtaskTest模块的setSearchParamsForLinkCaseTest方法，参数是$normalProduct, $task, 'bystory', 0  @0
- 执行testtaskTest模块的setSearchParamsForLinkCaseTest方法，参数是$normalProduct, $task, 'bymodule', 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zenData('user')->gen(5);
zenData('build')->gen(1);
zenData('story')->gen(10);

su('admin');

$testtaskTest = new testtaskZenTest();

// 创建正常产品对象
$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->name = 'Normal Product';
$normalProduct->shadow = 0;
$normalProduct->type = 'normal';

// 创建影子产品对象
$shadowProduct = new stdclass();
$shadowProduct->id = 2;
$shadowProduct->name = 'Shadow Product';
$shadowProduct->shadow = 1;
$shadowProduct->type = 'normal';

// 创建多分支产品对象
$branchProduct = new stdclass();
$branchProduct->id = 3;
$branchProduct->name = 'Branch Product';
$branchProduct->shadow = 0;
$branchProduct->type = 'branch';

// 创建测试单对象
$task = new stdclass();
$task->id = 1;
$task->build = 1;
$task->branch = '1';

r($testtaskTest->setSearchParamsForLinkCaseTest($normalProduct, $task, '', 0)) && p() && e('0');
r($testtaskTest->setSearchParamsForLinkCaseTest($branchProduct, $task, 'bystory', 1)) && p() && e('0');
r($testtaskTest->setSearchParamsForLinkCaseTest($shadowProduct, $task, '', 0)) && p() && e('0');
r($testtaskTest->setSearchParamsForLinkCaseTest($normalProduct, $task, 'bystory', 0)) && p() && e('0');
r($testtaskTest->setSearchParamsForLinkCaseTest($normalProduct, $task, 'bymodule', 1)) && p() && e('0');