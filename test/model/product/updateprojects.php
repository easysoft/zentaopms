#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->updateProjects();
cid=1
pid=1

测试更新产品101的项目信息 >> 731,1,,1,731,
测试更新产品102的项目信息 >> 0
测试更新产品103的项目信息 >> 0
测试更新产品111的项目信息 >> 733,1,,1,733,;734,1,,1,734,;735,1,,1,735,;736,1,,1,736,;737,1,,1,737,
测试更新产品112的项目信息 >> 738,1,,1,738,;739,1,,1,739,;740,1,,1,740,;741,1,,1,741,;742,1,,1,742,

*/
$productIDList = array('101', '102', '103', '111', '112');

$product = new productTest('admin');

r($product->updateProjectsTest($productIDList[0])) && p('0:id,parent,path')                                                                     && e('731,1,,1,731,');                                                         // 测试更新产品101的项目信息
r($product->updateProjectsTest($productIDList[1])) && p('0:id,parent,path')                                                                     && e('0');                                                                     // 测试更新产品102的项目信息
r($product->updateProjectsTest($productIDList[2])) && p('0:id,parent,path')                                                                     && e('0');                                                                     // 测试更新产品103的项目信息
r($product->updateProjectsTest($productIDList[3])) && p('0:id,parent,path;1:id,parent,path;2:id,parent,path;3:id,parent,path;4:id,parent,path') && e('733,1,,1,733,;734,1,,1,734,;735,1,,1,735,;736,1,,1,736,;737,1,,1,737,'); // 测试更新产品111的项目信息
r($product->updateProjectsTest($productIDList[4])) && p('0:id,parent,path;1:id,parent,path;2:id,parent,path;3:id,parent,path;4:id,parent,path') && e('738,1,,1,738,;739,1,,1,739,;740,1,,1,740,;741,1,,1,741,;742,1,,1,742,'); // 测试更新产品112的项目信息
