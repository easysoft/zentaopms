#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getBugAssign();
cid=1
pid=1

判断已分配的bug数据获取是否正确。                                                                                           >> 1
1.用户有此产品的权限并且此产品存在关联的项目。2。有访问项目的权限。--- 产品名称显示为链接，1.跳向项目页面，2.跳向产品页面。 >> 1
用户没有访问权限的时候，产品名称显示为原本的名称，并且不可点击。                                                            >> 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';
su('admin');

zdTable('bug')->config('bug_getbugassign')->gen(10);
zdTable('product')->gen(10);
zdTable('project')->gen(1);
zdTable('project')->config('project')->gen(10, false, false);
zdTable('projectproduct')->gen(5);
zdTable('user')->gen(2);


$pivot = new pivotTest();

$result = $pivot->getBugAssign();
r($result) && p('0:product,assignedTo,total;9:product,assignedTo,total') && e('1,admin,10;10,admin,10');    //判断已分配的bug数据获取是否正确。

foreach($result as $row) 
{
    $productName = $row->productName;
    $row->isProductNameHtml = false;
    if($productName != strip_tags($productName)) $row->isProductNameHtml = true;
}

$row1 = $result[0];
$row2 = $result[9];
$condition1 = $row1->product == 1  && $row1->isProductNameHtml && strip_tags($row1->productName) != $row1->productName && strpos($row1->productName, 'm=project&f=view') !== false;
$condition2 = $row2->product == 10 && $row1->isProductNameHtml && strip_tags($row2->productName) != $row2->productName && strpos($row2->productName, 'm=product&f=view') !== false;

r($condition1 && $condition2) && p('') && e('1');  //1.用户有此产品的权限并且此产品存在关联的项目。2。有访问项目的权限。--- 产品名称显示为链接，1.跳向项目页面，2.跳向产品页面。

su('user1');

$result = $pivot->getBugAssign();
foreach($result as $row) 
{
    $productName = $row->productName;
    $row->isProductNameHtml = false;
    if($productName != strip_tags($productName)) $row->isProductNameHtml = true;
}

$row3 = $result[0];
$row4 = $result[9];
$condition3 = $row3->product == 1  && !$row3->isProductNameHtml && strip_tags($row3->productName) == $row3->productName;
$condition4 = $row4->product == 10 && !$row4->isProductNameHtml && strip_tags($row4->productName) == $row4->productName;

r($condition3 && $condition4) && p('') && e('1');   //用户没有访问权限的时候，产品名称显示为原本的名称，并且不可点击。
