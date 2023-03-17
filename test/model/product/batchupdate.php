#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试 productModel->batchUpdate();
cid=1
pid=1

批量修改product名称 >> name,正常产品1,批量修改产品1
批量修改produc测试负责人 >> QD,test2,test1
批量修改product状态 >> status,normal,closed

*/

$names      = array('1' => '批量修改产品1', '2' => '正常产品2', '3' => '正常产品3');
$QDs        = array('1' => 'test1', '2' => 'test1', '3' => 'test3');
$status     = array('1' => 'normal', '2' => 'normal', '3' => 'closed');

$changeName   = array('names' => $names);
$changeQDs    = array('QDs' => $QDs);
$changeStatus = array('statuses' => $status);

$product = new productTest('admin');

r($product->batchUpdateTest($changeName, '1'))   && p('0:field,old,new') && e('name,正常产品1,批量修改产品1'); // 批量修改product名称
r($product->batchUpdateTest($changeQDs, '2'))    && p('0:field,old,new') && e('QD,test2,test1');               // 批量修改produc测试负责人
r($product->batchUpdateTest($changeStatus, '3')) && p('0:field,old,new') && e('status,normal,closed');         // 批量修改product状态
