#!/usr/bin/env php
<?php

/**

title=productTao->getExecutionCountPairs();
timeout=0
cid=17544

- 获取ID为1的产品关联的迭代数量 @0
- 获取ID为2的产品关联的迭代数量 @0
- 获取ID为3的产品关联的迭代数量 @1
- 获取ID为4的产品关联的迭代数量 @1
- 获取ID为11的产品关联的迭代数量 @1
- 获取ID为100的产品关联的迭代数量 @1
- 获取ID为10000的产品关联的迭代数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('projectproduct')->loadYaml('projectproduct')->gen(100);
zenData('project')->loadYaml('project')->gen(100);

$product = new productTest('admin');
$pairs = $product->objectModel->getExecutionCountPairs(array(1,2,3,4,5,6,7,8,9,10,11,100,10000));

r(isset($pairs[1]))     && p() && e('0'); //获取ID为1的产品关联的迭代数量
r(isset($pairs[2]))     && p() && e('0'); //获取ID为2的产品关联的迭代数量
r($pairs[3])            && p() && e('1'); //获取ID为3的产品关联的迭代数量
r($pairs[4])            && p() && e('1'); //获取ID为4的产品关联的迭代数量
r($pairs[11])           && p() && e('1'); //获取ID为11的产品关联的迭代数量
r($pairs[100])          && p() && e('1'); //获取ID为100的产品关联的迭代数量
r(isset($pairs[10000])) && p() && e('0'); //获取ID为10000的产品关联的迭代数量
