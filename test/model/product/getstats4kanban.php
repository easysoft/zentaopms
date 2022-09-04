#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getStats4Kanban();
cid=1
pid=1

测试获取产品的看板stats信息 >> 项目集1,项目集2,项目集3
测试获取产品的看板stats信息 >> 正常产品1;正常产品2;正常产品3
测试获取产品的看板stats信息 >> 项目3;项目4;项目5
测试获取产品的看板stats信息 >> 迭代544;迭代546;迭代552
测试获取产品的看板stats信息 >> 44.44,9;100,10
测试获取产品的看板stats信息 >> 13
测试获取产品的看板stats信息 >> 22
测试获取产品的看板stats信息 >> 72
测试获取产品的看板stats信息 >> 2

*/

$product = new productTest('admin');

$typeList = array('programList', 'productList', 'planList', 'projectList', 'executionList', 'projectProduct', 'projectLatestExecutions', 'hourList', 'releaseList');

r($product->getStats4KanbanTest($typeList[0]))       && p('1,2,3')                                         && e('项目集1,项目集2,项目集3');       // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[1]))       && p('1:name;2:name;3:name')                          && e('正常产品1;正常产品2;正常产品3'); // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[3]))       && p('13:name;14:name;15:name')                       && e('项目3;项目4;项目5');             // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[6]))       && p('14:name;16:name;22:name')                       && e('迭代544;迭代546;迭代552');       // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[7]))       && p('612:progress,totalReal;618:progress,totalReal') && e('44.44,9;100,10');                // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[2], true)) && p()                                                && e('13');                            // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[4], true)) && p()                                                && e('22');                            // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[5], true)) && p()                                                && e('72');                            // 测试获取产品的看板stats信息
r($product->getStats4KanbanTest($typeList[8], true)) && p()                                                && e('2');                             // 测试获取产品的看板stats信息
