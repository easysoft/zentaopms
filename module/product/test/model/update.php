#!/usr/bin/env php
<?php

/**

title=测试productModel->update();
cid=0

- 测试更新产品名称
 - 第0条的field属性 @name
 - 第0条的old属性 @正常产品2
 - 第0条的new属性 @john
- 测试更新产品代号
 - 第0条的field属性 @code
 - 第0条的old属性 @code2
 - 第0条的new属性 @newcode1
- 测试更新产品类型
 - 第0条的field属性 @type
 - 第0条的old属性 @normal
 - 第0条的new属性 @branch
- 测试还原产品类型
 - 第0条的field属性 @type
 - 第0条的old属性 @branch
 - 第0条的new属性 @normal
- 测试不更改产品名称
 - 第0条的field属性 @acl
 - 第0条的old属性 @open
 - 第0条的new属性 @private
- 测试不更改产品名称
 - 第0条的field属性 @whitelist
 - 第0条的new属性 @,test1,dev1,pm1
- 测试不更改产品名称 @0
- 测试不更改产品代号 @0
- 测试同一项目集下产品名称不能重复第name条的0属性 @『产品名称』已经有『john』这条记录了。
- 测试同一项目集下产品代号不能重复第code条的0属性 @『产品代号』已经有『newcode1』这条记录了。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';
su('admin');

zdTable('product')->gen(50);

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
