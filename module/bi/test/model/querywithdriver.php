#!/usr/bin/env php
<?php

/**

title=测试 biModel::queryWithDriver();
timeout=0
cid=15213

- 查询需求的所有字段
 - 第1条的title属性 @软件需求2
 - 第1条的stage属性 @wait
- 查询需求的id、titile
 - 第1条的id属性 @2
 - 第1条的title属性 @软件需求2
- 查询到的需求数量 @100
- 联表SQL语句查看产品名称第1条的name属性 @正常产品1
- 联表SQL语句别名第1条的productName属性 @正常产品1
- 查看使用limit语句获取到的数量 @1
- 查看使用limit语句获取到的数量 @10
- 查看产品下需求数量第0条的count属性 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('story')->gen(100);
zenData('product')->gen(50);
su('admin');

global $tester;
$tester->loadModel('bi');

$sql = array();
$sql[0] = 'select * from zt_story;';
$sql[1] = 'select id,title from zt_story;';
$sql[2] = 'select t1.id,t2.name from zt_story as t1 left join zt_product as t2 on t1.product = t2.id;';
$sql[3] = 'select t1.id as storyID,t2.name as productName from zt_story as t1 left join zt_product as t2 on t1.product = t2.id;';
$sql[4] = 'select * from zt_story where id = 1;';
$sql[5] = 'select * from zt_story limit 10;';
$sql[6] = 'select product, count(*) as count from zt_story group by product;';

r($tester->bi->queryWithDriver('mysql', $sql[0]))        && p('1:title,stage') && e('软件需求2,wait'); // 查询需求的所有字段

r($tester->bi->queryWithDriver('mysql', $sql[1]))        && p('1:id,title')    && e('2,软件需求2');    // 查询需求的id、titile
r(count($tester->bi->queryWithDriver('mysql', $sql[1]))) && p('')   	       && e('100');            // 查询到的需求数量

r($tester->bi->queryWithDriver('mysql', $sql[2])) && p('1:name') && e('正常产品1'); // 联表SQL语句查看产品名称

r($tester->bi->queryWithDriver('mysql', $sql[3])) && p('1:productName') && e('正常产品1'); // 联表SQL语句别名

r(count($tester->bi->queryWithDriver('mysql', $sql[4]))) && p('') && e('1'); // 查看使用limit语句获取到的数量

r(count($tester->bi->queryWithDriver('mysql', $sql[5]))) && p('') && e('10'); // 查看使用limit语句获取到的数量

r($tester->bi->queryWithDriver('mysql', $sql[6])) && p('0:count') && e('4'); // 查看产品下需求数量