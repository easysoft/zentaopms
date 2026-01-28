#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::replace4Workflow();
timeout=0
cid=15960

- 执行dataviewTest模块的replace4WorkflowTest方法，参数是'产品名称'  @产品名称
- 执行dataviewTest模块的replace4WorkflowTest方法，参数是'这个产品很好'  @这个产品很好
- 执行dataviewTest模块的replace4WorkflowTest方法，参数是'用户管理系统'  @用户管理系统
- 执行dataviewTest模块的replace4WorkflowTest方法，参数是''  @0
- 执行dataviewTest模块的replace4WorkflowTest方法，参数是'产品列表和产品设置'  @产品列表和产品设置

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dataviewTest = new dataviewModelTest();

r($dataviewTest->replace4WorkflowTest('产品名称')) && p() && e('产品名称');
r($dataviewTest->replace4WorkflowTest('这个产品很好')) && p() && e('这个产品很好');
r($dataviewTest->replace4WorkflowTest('用户管理系统')) && p() && e('用户管理系统');
r($dataviewTest->replace4WorkflowTest('')) && p() && e('0');
r($dataviewTest->replace4WorkflowTest('产品列表和产品设置')) && p() && e('产品列表和产品设置');