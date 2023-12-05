#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedObjects();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(30);
zdTable('product')->config('product')->gen(20);
zdTable('user')->gen(5);
su('admin');

$types       = array('', 'product', 'project', 'execution');
$returnTypes = array('', 'merge');
$appends     = array(0, 1, 11, 101);

$docTester = new docTest();
r($docTester->getOrderedObjectsTest($types[0], $returnTypes[0], $appends[0])) && p()      && e('0');                 // 测试空数据
r($docTester->getOrderedObjectsTest($types[1], $returnTypes[0], $appends[0])) && p('1')   && e('产品1');             // 获取已排序的产品数据
r($docTester->getOrderedObjectsTest($types[1], $returnTypes[1], $appends[0])) && p('1')   && e('产品1');             // 获取已排序的产品数据
r($docTester->getOrderedObjectsTest($types[1], $returnTypes[0], $appends[1])) && p('1')   && e('产品1');             // 获取已排序包括id=1的产品数据
r($docTester->getOrderedObjectsTest($types[1], $returnTypes[1], $appends[1])) && p('1')   && e('产品1');             // 获取已排序包括id=1的产品数据
r($docTester->getOrderedObjectsTest($types[2], $returnTypes[0], $appends[0])) && p('11')  && e('敏捷项目1');         // 获取已排序的项目数据
r($docTester->getOrderedObjectsTest($types[2], $returnTypes[1], $appends[0])) && p('11')  && e('敏捷项目1');         // 获取已排序的项目数据
r($docTester->getOrderedObjectsTest($types[2], $returnTypes[0], $appends[1])) && p('11')  && e('敏捷项目1');         // 获取已排序包括id=11的项目数据
r($docTester->getOrderedObjectsTest($types[2], $returnTypes[1], $appends[1])) && p('11')  && e('敏捷项目1');         // 获取已排序包括id=11的项目数据
r($docTester->getOrderedObjectsTest($types[3], $returnTypes[0], $appends[0])) && p('102') && e('敏捷项目1 / 迭代6'); // 获取已排序的执行数据
r($docTester->getOrderedObjectsTest($types[3], $returnTypes[1], $appends[0])) && p('102') && e('敏捷项目1 / 迭代6'); // 获取已排序的执行数据
r($docTester->getOrderedObjectsTest($types[3], $returnTypes[0], $appends[1])) && p('101') && e('敏捷项目1 / 迭代5'); // 获取已排序包括id=101的执行数据
r($docTester->getOrderedObjectsTest($types[3], $returnTypes[1], $appends[1])) && p('101') && e('敏捷项目1 / 迭代5'); // 获取已排序包括id=101的执行数据
