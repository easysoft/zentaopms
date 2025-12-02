#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(100);
su('admin');

/**

title=测试 productModel->getlistbyacl();
timeout=0
cid=17494

- 获取权限为公开ID为1的产品属性。
 - 第1条的id属性 @1
 - 第1条的program属性 @0
 - 第1条的PO属性 @po1
 - 第1条的QD属性 @test1
 - 第1条的RD属性 @dev1
- 获取权限为公开ID为2的产品属性。
 - 第2条的id属性 @2
 - 第2条的program属性 @1
 - 第2条的PO属性 @po2
 - 第2条的QD属性 @test2
 - 第2条的RD属性 @dev2
- 获取权限为私有ID为100的产品属性。
 - 第100条的id属性 @100
 - 第100条的program属性 @0
 - 第100条的PO属性 @po10
 - 第100条的QD属性 @test100
 - 第100条的RD属性 @dev100
- 获取权限为私有ID为80的产品属性。
 - 第80条的id属性 @80
 - 第80条的program属性 @2
 - 第80条的PO属性 @po10
 - 第80条的QD属性 @test80
 - 第80条的RD属性 @dev80
- 获取权限为私有ID为60的产品属性。
 - 第60条的id属性 @60
 - 第60条的program属性 @4
 - 第60条的PO属性 @po10
 - 第60条的QD属性 @test60
 - 第60条的RD属性 @dev60

*/

global $tester;
$tester->loadModel('product');

r($tester->product->getlistbyacl('open'))    && p('1:id,program,PO,QD,RD')   && e('1,0,po1,test1,dev1');        // 获取权限为公开ID为1的产品属性。
r($tester->product->getlistbyacl('open'))    && p('2:id,program,PO,QD,RD')   && e('2,1,po2,test2,dev2');        // 获取权限为公开ID为2的产品属性。
r($tester->product->getlistbyacl('private')) && p('100:id,program,PO,QD,RD') && e('100,0,po10,test100,dev100'); // 获取权限为私有ID为100的产品属性。
r($tester->product->getlistbyacl('private')) && p('80:id,program,PO,QD,RD')  && e('80,2,po10,test80,dev80');    // 获取权限为私有ID为80的产品属性。
r($tester->product->getlistbyacl('private')) && p('60:id,program,PO,QD,RD')  && e('60,4,po10,test60,dev60');    // 获取权限为私有ID为60的产品属性。
