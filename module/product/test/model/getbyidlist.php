#!/usr/bin/env php
<?php

/**

title=productModel->getByIdList();
cid=0

- 测试获取产品列表中产品1的信息
 - 第1条的name属性 @产品1
 - 第1条的program属性 @1
 - 第1条的shadow属性 @0
 - 第1条的line属性 @1
 - 第1条的type属性 @normal
 - 第1条的status属性 @normal
 - 第1条的PO属性 @po1
 - 第1条的QD属性 @qd1
 - 第1条的acl属性 @open
 - 第1条的vision属性 @rnd
- 测试获取产品列表中产品2的信息
 - 第2条的name属性 @产品2
 - 第2条的program属性 @2
 - 第2条的shadow属性 @0
 - 第2条的line属性 @2
 - 第2条的type属性 @normal
 - 第2条的status属性 @normal
 - 第2条的PO属性 @po1
 - 第2条的QD属性 @qd1
 - 第2条的acl属性 @open
 - 第2条的vision属性 @rnd
- 测试获取产品列表中产品3的信息
 - 第3条的name属性 @产品3
 - 第3条的program属性 @3
 - 第3条的shadow属性 @1
 - 第3条的line属性 @3
 - 第3条的type属性 @branch
 - 第3条的status属性 @closed
 - 第3条的PO属性 @po2
 - 第3条的QD属性 @qd2
 - 第3条的acl属性 @private
 - 第3条的vision属性 @rnd
- 测试获取产品列表中产品4的信息
 - 第4条的name属性 @产品4
 - 第4条的program属性 @4
 - 第4条的shadow属性 @1
 - 第4条的line属性 @4
 - 第4条的type属性 @branch
 - 第4条的status属性 @closed
 - 第4条的PO属性 @po2
 - 第4条的QD属性 @qd2
 - 第4条的acl属性 @private
 - 第4条的vision属性 @rnd
- 测试获取产品列表中产品5的信息
 - 第5条的name属性 @产品5
 - 第5条的program属性 @5
 - 第5条的shadow属性 @1
 - 第5条的line属性 @5
 - 第5条的type属性 @branch
 - 第5条的status属性 @closed
 - 第5条的PO属性 @po3
 - 第5条的QD属性 @qd3
 - 第5条的acl属性 @private
 - 第5条的vision属性 @rnd

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(5);

$product = new productTest('admin');

$productIdList = array(1, 2, 3, 4, 5);

r($product->getByIdListTest($productIdList)) && p('1:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品1,1,0,1,normal,normal,po1,qd1,open,rnd');    // 测试获取产品列表中产品1的信息
r($product->getByIdListTest($productIdList)) && p('2:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品2,2,0,2,normal,normal,po1,qd1,open,rnd');    // 测试获取产品列表中产品2的信息
r($product->getByIdListTest($productIdList)) && p('3:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品3,3,1,3,branch,closed,po2,qd2,private,rnd'); // 测试获取产品列表中产品3的信息
r($product->getByIdListTest($productIdList)) && p('4:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品4,4,1,4,branch,closed,po2,qd2,private,rnd'); // 测试获取产品列表中产品4的信息
r($product->getByIdListTest($productIdList)) && p('5:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品5,5,1,5,branch,closed,po3,qd3,private,rnd'); // 测试获取产品列表中产品5的信息
