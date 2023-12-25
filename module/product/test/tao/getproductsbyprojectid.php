#!/usr/bin/env php
<?php

/**

title=productTao->getProductsByProjectID();
cid=0

- 获取敏捷项目关联的产品数据
 - 第0条的name属性 @产品1
 - 第0条的status属性 @normal
- 获取瀑布项目关联的产品数据
 - 第0条的name属性 @产品2
 - 第0条的status属性 @normal
- 获取看板项目关联的产品数据
 - 第0条的name属性 @产品4
 - 第0条的status属性 @normal
- 获取敏捷项目关联的未关闭产品数据
 - 第0条的name属性 @产品1
 - 第0条的status属性 @normal
- 获取瀑布项目关联的未关闭产品数据
 - 第0条的name属性 @产品2
 - 第0条的status属性 @normal
- 获取看板项目关联的未关闭产品数据
 - 第0条的name属性 @产品4
 - 第0条的status属性 @normal
- 获取敏捷项目关联的所有产品数据
 - 第0条的name属性 @产品1
 - 第0条的status属性 @normal
- 获取瀑布项目关联的所有产品数据
 - 第0条的name属性 @产品2
 - 第0条的status属性 @normal
- 获取看板项目关联的所有产品数据
 - 第0条的name属性 @产品4
 - 第0条的status属性 @normal
- 获取敏捷项目关联的产品数据
 - 第0条的name属性 @产品1
 - 第0条的status属性 @normal
- 获取瀑布项目关联的产品数据
 - 第0条的name属性 @产品2
 - 第0条的status属性 @normal
- 获取看板项目关联的产品数据
 - 第0条的name属性 @产品4
 - 第0条的status属性 @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('product')->config('product')->gen(10);
zdTable('projectproduct')->config('projectproduct')->gen(10);
su('admin');

$projectIdList = array(11, 60, 100);
$appendID      = '10';
$statusList    = array('', 'noclosed');
$orderBy       = 'id_desc';
$withDeleted   = array(false, true);

global $tester;
$tester->loadModel('product');
r($tester->product->getProductsByProjectID($projectIdList[0], '',        $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品1,normal'); // 获取敏捷项目关联的产品数据
r($tester->product->getProductsByProjectID($projectIdList[1], '',        $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品2,normal'); // 获取瀑布项目关联的产品数据
r($tester->product->getProductsByProjectID($projectIdList[2], '',        $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品4,normal'); // 获取看板项目关联的产品数据
r($tester->product->getProductsByProjectID($projectIdList[0], '',        $statusList[1], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品1,normal'); // 获取敏捷项目关联的未关闭产品数据
r($tester->product->getProductsByProjectID($projectIdList[1], '',        $statusList[1], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品2,normal'); // 获取瀑布项目关联的未关闭产品数据
r($tester->product->getProductsByProjectID($projectIdList[2], '',        $statusList[1], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品4,normal'); // 获取看板项目关联的未关闭产品数据
r($tester->product->getProductsByProjectID($projectIdList[0], '',        $statusList[0], $orderBy, $withDeleted[1])) && p('0:name,status') && e('产品1,normal'); // 获取敏捷项目关联的所有产品数据
r($tester->product->getProductsByProjectID($projectIdList[1], '',        $statusList[0], $orderBy, $withDeleted[1])) && p('0:name,status') && e('产品2,normal'); // 获取瀑布项目关联的所有产品数据
r($tester->product->getProductsByProjectID($projectIdList[2], '',        $statusList[0], $orderBy, $withDeleted[1])) && p('0:name,status') && e('产品4,normal'); // 获取看板项目关联的所有产品数据
r($tester->product->getProductsByProjectID($projectIdList[0], $appendID, $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品1,normal'); // 获取敏捷项目关联的产品数据
r($tester->product->getProductsByProjectID($projectIdList[1], $appendID, $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品2,normal'); // 获取瀑布项目关联的产品数据
r($tester->product->getProductsByProjectID($projectIdList[2], $appendID, $statusList[0], $orderBy, $withDeleted[0])) && p('0:name,status') && e('产品4,normal'); // 获取看板项目关联的产品数据
