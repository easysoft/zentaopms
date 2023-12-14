#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionProducts();
cid=1;

- 测试拿到的 admin 数据是否正确 id,name,type,PO,reviewer
 - 第1条的id属性 @1
 - 第1条的name属性 @Test product
 - 第1条的type属性 @normal
 - 第1条的PO属性 @admin
 - 第1条的reviewer属性 @admin
- 测试拿到的 admin 数据是否正确 plans第1[plans]条的1属性 @Test plan
- 测试拿到的 user1 数据是否正确 id,name,type,PO,reviewer
 - 第1条的id属性 @1
 - 第1条的name属性 @Test product
 - 第1条的type属性 @normal
 - 第1条的PO属性 @user1
 - 第1条的reviewer属性 @user1
- 测试拿到的 user1 数据是否正确 plans第1[plans]条的1属性 @Test plan

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
$products = $tutorial->getExecutionProductsTest();
r($products) && p('1:id,name,type,PO,reviewer') && e('1,Test product,normal,admin,admin'); // 测试拿到的 admin 数据是否正确 id,name,type,PO,reviewer
r($products) && p('1[plans]:1')                 && e('Test plan');                         // 测试拿到的 admin 数据是否正确 plans

su('user1');
$products = $tutorial->getExecutionProductsTest();
r($products) && p('1:id,name,type,PO,reviewer') && e('1,Test product,normal,user1,user1'); // 测试拿到的 user1 数据是否正确 id,name,type,PO,reviewer
r($products) && p('1[plans]:1')                 && e('Test plan');                         // 测试拿到的 user1 数据是否正确 plans
