#!/usr/bin/env php
<?php

/**
title=测试 pivotTao->getProductList();
cid=17446

- 查询所有的已关闭产品
 - 第1条的id属性 @1
 - 第1条的name属性 @正常产品1
 - 第9条的id属性 @9
 - 第9条的name属性 @正常产品9
- 查询所有的未关闭产品
 - 第1条的id属性 @1
 - 第1条的name属性 @正常产品1
 - 第10条的id属性 @10
 - 第10条的name属性 @正常产品10
- 查询所有的已关闭产品，并且id在1,2,3,5中
 - 第1条的id属性 @1
 - 第5条的id属性 @5
- 查询所有的未关闭产品，并且id在5,7,8,9中
 - 第5条的id属性 @5
 - 第7条的id属性 @7
 - 第9条的id属性 @9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('product')->loadYaml('product_closed')->gen(10);
zenData('project')->gen(10);

global $tester;

$pivot = new pivotTest();

$conditionList = array('', 'closedProduct');
$IDList        = array(array(), array(1, 2, 3, 5), array(5, 7 ,8, 9));

r($pivot->getProductList($conditionList[0], $IDList[0])) && p('1:id,name;9:id,name')  && e('1,正常产品1;9,正常产品9');      //查询所有的已关闭产品
r($pivot->getProductList($conditionList[1], $IDList[0])) && p('1:id,name;10:id,name') && e('1,正常产品1;10,正常产品10');    //查询所有的未关闭产品
r($pivot->getProductList($conditionList[0], $IDList[1])) && p('1:id;5:id')            && e('1,5');                          //查询所有的已关闭产品，并且id在1,2,3,5中
r($pivot->getProductList($conditionList[1], $IDList[2])) && p('5:id;7:id;9:id')       && e('5,7,9');                        //查询所有的未关闭产品，并且id在5,7,8,9中
