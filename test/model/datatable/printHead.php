#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/datatable.class.php';
su('admin');

/**

title=测试 datatableModel::printHead();
cid=1
pid=1

获取产品模块browse方法头部html           >> 1
获取产品模块browse方法头部html带排序     >> 1
获取产品模块browse方法头部html带参数     >> 1
获取产品模块browse方法头部html带checkbox >> 1

*/

$datatable = new datatableTest();
$productSetting = $datatable->getSettingTest('product', 'browse');

$data       = array('cols' => $productSetting[0], 'orderBy' => '', 'vars' => '', 'checkBox' => false);
$data_order = array('cols' => $productSetting[0], 'orderBy' => 'id_asc', 'vars' => '', 'checkBox' => false);
$data_vars  = array('cols' => $productSetting[0], 'orderBy' => 'id_asc', 'vars' => 'productID=1', 'checkBox' => false);
$data_check = array('cols' => $productSetting[0], 'orderBy' => 'id_asc', 'vars' => 'productID=1', 'checkBox' => true);

r($datatable->printHeadTest($data))       && p() && e(1);       //获取产品模块browse方法头部html
r($datatable->printHeadTest($data_order)) && p() && e(1);       //获取产品模块browse方法头部html带排序
r($datatable->printHeadTest($data_vars))  && p() && e(1);       //获取产品模块browse方法头部html带参数
r($datatable->printHeadTest($data_check)) && p() && e(1);       //获取产品模块browse方法头部html带checkbox
