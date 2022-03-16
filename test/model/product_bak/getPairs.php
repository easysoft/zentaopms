#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productBox.class.php';

/**

title=测试productModel->getPairs();
cid=1
pid=1

测试项目集10下的11号产品 >> 正常产品11
测试项目集10下的55号产品 >> 多分支产品55
测试项目集10下的99号产品 >> 多平台产品99
测试不存在的项目集 >> 没有数据
返回所有产品的数量 >> 100
返回项目集10下的所有产品 >> 9
测试项目集10下的未关闭产品5 >> 正常产品6
返回项目集10下的未关闭产品的数量 >> 5
测试顺序program_desc >> 1
测试顺序program_asc >> 1
测试顺序type_desc >> 1

*/

$tester = new productBox('admin');
var_dump($tester->getProductPairsByOrder(10, '', 'program_desc'));die;
r($tester->getProductPairs(10)) && p('11') && e('正常产品11');               // 测试项目集10下的11号产品
r($tester->getProductPairs(10)) && p('55') && e('多分支产品55');             // 测试项目集10下的55号产品
r($tester->getProductPairs(10)) && p('99') && e('多平台产品99');             // 测试项目集10下的99号产品
r($tester->getProductPairs(11)) && p()     && e('没有数据');                 // 测试不存在的项目集

r($tester->getProductPairsCount(0))   && p()    && e('100');                 // 返回所有产品的数量
r($tester->getProductPairsCount(10))  && p()    && e('9');                   // 返回项目集10下的所有产品
r($tester->getNoclosedPairs(5))       && p('6') && e('正常产品6');           // 测试项目集10下的未关闭产品5
r($tester->getNoclosedPairsCount(10)) && p()    && e('5');                   // 返回项目集10下的未关闭产品的数量

r($tester->getProductPairsByOrder(10, '', 'program_desc')) && p() && e('1'); // 测试顺序program_desc
r($tester->getProductPairsByOrder(11, '', 'program_asc'))  && p() && e('1'); // 测试顺序program_asc
r($tester->getProductPairsByOrder(11, '', 'type_desc'))    && p() && e('1'); // 测试顺序type_desc