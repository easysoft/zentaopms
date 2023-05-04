#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php"; su('admin');
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

/**

title=测试productModel->update();
cid=1
pid=1

*/

$product = new productTest('admin');

$updateName      = array('name' => 'john');
$updateCode      = array('code' => 'newcode1');
$updateType      = array('type' => 'branch');
$revertType      = array('type' => 'normal');
$updateAcl       = array('acl' => 'private');
$updateWhitelist = array('whitelist' => ',test1,dev1,pm1');
$uniqueName      = array('name' => 'john');
$uniqueCode      = array('code' => 'newcode1');
$repeatProduct   = array('name' => 'john');
$repeatCode      = array('code' => 'newcode1');

r($product->updateObject(2,  $updateName))     && p('0:field,old,new') && e('name,正常产品2,john'); // 测试更新产品名称
r($product->updateObject(2,  $updateCode))     && p('0:field,old,new') && e('code,code2,newcode1'); // 测试更新产品代号
r($product->updateObject(2,  $updateType))     && p('0:field,old,new') && e('type,normal,branch');   // 测试更新产品类型
r($product->updateObject(2,  $revertType))     && p('0:field,old,new') && e('type,branch,normal');   // 测试还原产品类型
r($product->updateObject(2,  $updateAcl))      && p('0:field,old,new') && e('acl,open,private');                 // 测试不更改产品名称
r($product->updateObject(2,  $updateWhitelist))&& p('0:field/new', '/') && e('whitelist/,test1,dev1,pm1');                 // 测试不更改产品名称
r($product->updateObject(2,  $uniqueName))     && p()          && e('0');                 // 测试不更改产品名称
r($product->updateObject(2,  $uniqueCode))     && p()          && e('0');                 // 测试不更改产品代号
r($product->updateObject(13, $repeatProduct))  && p('name:0')  && e('『产品名称』已经有『john』这条记录了。');     // 测试同一项目集下产品名称不能重复
r($product->updateObject(13, $repeatCode))     && p('code:0')  && e('『产品代号』已经有『newcode1』这条记录了。'); // 测试同一项目集下产品代号不能重复
