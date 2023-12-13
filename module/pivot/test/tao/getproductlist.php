#!/usr/bin/env php
<?php

/**
title=测试 pivotModel->getBugs();
cid=1
pid=1

查询所有的已关闭产品    >> 1,正常产品1;9,正常产品9
查询所有的未关闭产品    >> 1,正常产品1;10,正常产品10
查询所有的已关闭产品，并且id在1,2,3,5中    >> 1,5
查询所有的未关闭产品，并且id在5,7,8,9中    >> 5,7,9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('product')->config('product_closed')->gen(10);
zdTable('project')->gen(10);

global $tester;

$pivot = new pivotTest();

$conditionList = array('', 'closedProduct');
$IDList        = array(array(), array(1, 2, 3, 5), array(5, 7 ,8, 9));

r($pivot->getProductList($conditionList[0], $IDList[0])) && p('1:id,name;9:id,name')  && e('1,正常产品1;9,正常产品9');      //查询所有的已关闭产品
r($pivot->getProductList($conditionList[1], $IDList[0])) && p('1:id,name;10:id,name') && e('1,正常产品1;10,正常产品10');    //查询所有的未关闭产品
r($pivot->getProductList($conditionList[0], $IDList[1])) && p('1:id;5:id')            && e('1,5');                          //查询所有的已关闭产品，并且id在1,2,3,5中
r($pivot->getProductList($conditionList[1], $IDList[2])) && p('5:id;7:id;9:id')       && e('5,7,9');                        //查询所有的未关闭产品，并且id在5,7,8,9中
