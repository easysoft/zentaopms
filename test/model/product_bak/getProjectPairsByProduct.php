#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productBox.class.php';

/**

title=productModel->getProjectPairsByProduct();
cid=1
pid=1

返回产品1关联的项目11名字 >> 项目1
返回产品1关联的项目21名字 >> 项目11
传入不存在的产品 >> 没有数据
返回id为15的项目名 >> 项目5
传入不存在的项目id >> 没有数据

*/

$tester = new productBox('admin');

r($tester->getProjectPairsByProductID(1))   && p('11') && e('项目1');    // 返回产品1关联的项目11名字
r($tester->getProjectPairsByProductID(1))   && p('21') && e('项目11');   // 返回产品1关联的项目21名字
r($tester->getProjectPairsByProductID(101)) && p()     && e('没有数据'); // 传入不存在的产品

r($tester->getAppendProject(15))  && p('15') && e('项目5');    // 返回id为15的项目名
r($tester->getAppendProject(701)) && p()     && e('没有数据'); // 传入不存在的项目id