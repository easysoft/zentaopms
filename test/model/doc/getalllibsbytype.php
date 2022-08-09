#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getAllLibsByType();
cid=1
pid=1

collector类型查询 >> 0
all类型查询 >> 0
product类型查询 >> 正常产品2,po2
product类型传入产品id查询 >> 正常产品3,po3
product类型查询统计 >> 100
product类型传入产品id查询统计 >> 100
execution类型查询 >> 迭代1,
execution类型传入产品id查询 >> 看板62,
execution类型查询统计 >> 14
execution类型传入产品id查询统计 >> 630

*/
$types   = array('collector', 'all', 'product', 'execution');
$product = array('1', '');

$doc = new docTest();

r($doc->getAllLibsByTypeTest($types[0], $product[0]))        && p()                     && e('0');            //collector类型查询
r($doc->getAllLibsByTypeTest($types[1], $product[0]))        && p()                     && e('0');            //all类型查询
r($doc->getAllLibsByTypeTest($types[2], $product[0]))        && p('2:name,createdBy')   && e('正常产品2,po2');//product类型查询
r($doc->getAllLibsByTypeTest($types[2], $product[1]))        && p('3:name,createdBy')   && e('正常产品3,po3');//product类型传入产品id查询
r(count($doc->getAllLibsByTypeTest($types[2], $product[0]))) && p()                     && e('100');          //product类型查询统计
r(count($doc->getAllLibsByTypeTest($types[2], $product[1]))) && p()                     && e('100');          //product类型传入产品id查询统计
r($doc->getAllLibsByTypeTest($types[3], $product[0]))        && p('101:name,createdBy') && e('迭代1,');       //execution类型查询
r($doc->getAllLibsByTypeTest($types[3], $product[1]))        && p('162:name,createdBy') && e('看板62,');      //execution类型传入产品id查询
r(count($doc->getAllLibsByTypeTest($types[3], $product[0]))) && p()                     && e('14');           //execution类型查询统计
r(count($doc->getAllLibsByTypeTest($types[3], $product[1]))) && p()                     && e('630');          //execution类型传入产品id查询统计