#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getProjectListByProduct();
cid=1
pid=1

返回产品1关联的项目11名字 >> 项目1
返回产品1关联的项目21名字 >> 项目11
传入不存在的产品 >> 没有数据

*/

$product = new productTest('admin');

$t_retion = array('1', '1', '10001');

r($product->getAllProjectsByProduct($t_retion[0]))   && p('11:name') && e('项目1');     // 返回产品1关联的项目11名字
r($product->getAllProjectsByProduct($t_retion[1]))   && p('21:name') && e('项目11');    // 返回产品1关联的项目21名字
r($product->getAllProjectsByProduct($t_retion[2]))   && p()          && e('没有数据');  // 传入不存在的产品