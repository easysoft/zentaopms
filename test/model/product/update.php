#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->update();
cid=1
pid=1

测试更新产品名称 >> name,正常产品2,john
测试更新产品代号 >> code,code2,newcode1
测试更新产品名称和代号 >> name,john,jack;code,newcode1,newcode2
测试不更改产品名称 >> 没有数据更新
测试不更改产品代号 >> 没有数据更新
测试同一项目集下产品名称不能重复 >> 『产品名称』已经有『jack』这条记录了。
测试同一项目集下产品代号不能重复 >> 『产品代号』已经有『newcode2』这条记录了。

*/

$product = new productTest('admin');

$t_upname = array('name' => 'john');
$t_upid = array('code' => 'newcode1');
$t_idname = array('name' => 'jack', 'code' => 'newcode2');
$t_unname = array('name' => 'jack');
$t_unid = array('code' => 'newcode2');
$t_repeaproduct = array('name' => 'jack');
$t_repeatid = array('code' => 'newcode2');

r($product->updateObject('product', 2, $t_upname))      && p('0:field,old,new') && e('name,正常产品2,john'); // 测试更新产品名称
r($product->updateObject('product', 2, $t_upid))        && p('0:field,old,new') && e('code,code2,newcode1'); // 测试更新产品代号
r($product->updateObject('product', 2, $t_idname))      && p('0:field,old,new;1:field,old,new') && e('name,john,jack;code,newcode1,newcode2'); // 测试更新产品名称和代号
r($product->updateObject('product', 2, $t_unname))      && p()          && e('没有数据更新');                 // 测试不更改产品名称
r($product->updateObject('product', 2, $t_unid))        && p()          && e('没有数据更新');                 // 测试不更改产品代号
r($product->updateObject('product', 13, $t_repeaproduct)) && p('name:0')  && e('『产品名称』已经有『jack』这条记录了。');     // 测试同一项目集下产品名称不能重复
r($product->updateObject('product', 13, $t_repeatid))   && p('code:0')  && e('『产品代号』已经有『newcode2』这条记录了。'); // 测试同一项目集下产品代号不能重复
