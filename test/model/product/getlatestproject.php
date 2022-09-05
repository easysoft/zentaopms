#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->getLatestProject();
cid=1
pid=1

测试产品25关联的最后一个未关闭的项目,按begin字段排序 >> 405
测试产品38关联的最后一个未关闭的项目,按begin字段排序 >> 408
传入不存在的产品 >> 没有数据

*/

$product = new productTest('admin');

$t_project25 = array('id'=>'25');
$t_project38 = array('id'=>'38');
$t_project101 = array('id'=>'10001');

r($product->testGetLatestProject($t_project25['id']))  && p('id') && e('405');      // 测试产品25关联的最后一个未关闭的项目,按begin字段排序
r($product->testGetLatestProject($t_project38['id']))  && p('id') && e('408');      // 测试产品38关联的最后一个未关闭的项目,按begin字段排序
r($product->testGetLatestProject($t_project101['id'])) && p()     && e('没有数据'); // 传入不存在的产品
