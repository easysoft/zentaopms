#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('branch')->loadYaml('branch')->gen(10);
zenData('product')->loadYaml('product')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('user')->gen(5);
su('admin');

/**

title=测试 branchModel->getPairs();
timeout=0
cid=15327

- 获取正常产品下的分支信息 @0
- 获取多分支产品下的分支信息属性1 @分支1
- 获取不存在产品下的分支信息 @0
- 获取正常产品下的所有分支信息 @0
- 获取多分支产品的所有分支信息属性1 @分支1
- 获取不存在产品的所有分支信息 @0
- 获取正常产品下的激活分支信息 @0
- 获取多分支产品的激活分支信息属性1 @分支1
- 获取不存在产品的激活分支信息 @0
- 获取正常产品下的非主干分支信息 @0
- 获取多分支产品的非主干分支信息属性1 @分支1
- 获取不存在产品的非主干分支信息 @0
- 获取正常产品下的包括关闭的分支信息 @0
- 获取多分支产品的包括关闭的分支信息属性1 @分支1
- 获取不存在产品的包括关闭的分支信息 @0
- 获取执行下正常产品下的分支信息 @0
- 获取执行下多分支产品下的分支信息 @0
- 获取执行下不存在产品下的分支信息 @0
- 获取执行下正常产品下的所有分支信息 @0
- 获取执行下多分支产品的所有分支信息 @0
- 获取执行下不存在产品的所有分支信息 @0
- 获取执行下正常产品下的激活分支信息 @0
- 获取执行下多分支产品的激活分支信息 @0
- 获取执行下不存在产品的激活分支信息 @0
- 获取执行下正常产品下的非主干分支信息 @0
- 获取执行下多分支产品的非主干分支信息 @0
- 获取执行下不存在产品的非主干分支信息 @0
- 获取执行下正常产品下的包括关闭的分支信息 @0
- 获取执行下多分支产品的包括关闭的分支信息 @0
- 获取执行下不存在产品的包括关闭的分支信息 @0
- 获取执行下正常产品下的分支信息 @0
- 获取不存在执行下多分支产品下的分支信息 @0
- 获取不存在执行下不存在产品下的分支信息 @0
- 获取不存在执行下正常产品下的所有分支信息 @0
- 获取不存在执行下多分支产品的所有分支信息 @0
- 获取不存在执行下不存在产品的所有分支信息 @0
- 获取不存在执行下正常产品下的激活分支信息 @0
- 获取不存在执行下多分支产品的激活分支信息 @0
- 获取不存在执行下不存在产品的激活分支信息 @0
- 获取不存在执行下正常产品下的非主干分支信息 @0
- 获取不存在执行下多分支产品的非主干分支信息 @0
- 获取不存在执行下不存在产品的非主干分支信息 @0
- 获取不存在执行下正常产品下的包括关闭的分支信息 @0
- 获取不存在执行下多分支产品的包括关闭的分支信息 @0
- 获取不存在执行下不存在产品的包括关闭的分支信息 @0
- 获取不包括分支1、2正常产品下的分支信息 @0
- 获取不包括分支1、2多分支产品下的分支信息属性3 @分支3
- 获取不包括分支1、2不存在产品下的分支信息 @0
- 获取不包括分支1、2正常产品下的所有分支信息 @0
- 获取不包括分支1、2多分支产品的所有分支信息属性3 @分支3
- 获取不包括分支1、2不存在产品的所有分支信息 @0
- 获取不包括分支1、2正常产品下的激活分支信息 @0
- 获取不包括分支1、2多分支产品的激活分支信息 @主干
- 获取不包括分支1、2不存在产品的激活分支信息 @0
- 获取不包括分支1、2正常产品下的非主干分支信息 @0
- 获取不包括分支1、2多分支产品的非主干分支信息属性3 @分支3
- 获取不包括分支1、2不存在产品的非主干分支信息 @0
- 获取不包括分支1、2正常产品下的包括关闭的分支信息 @0
- 获取不包括分支1、2多分支产品的包括关闭的分支信息属性3 @分支3 (已关闭)
- 获取不包括分支1、2不存在产品的包括关闭的分支信息 @0

*/

$productIdList      = array(1, 6, 11);
$paramList          = array('', 'all', 'active', 'noempty', 'withClosed');
$executionIdList    = array(0, 101, 200);
$mergedBranchesList = array('', '1,2');

global $tester;
$tester->loadModel('branch');
r($tester->branch->getPairs($productIdList[0], $paramList[0], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取正常产品下的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[0], $executionIdList[0], $mergedBranchesList[0])) && p('1') && e('分支1'); // 获取多分支产品下的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[0], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取不存在产品下的分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[1], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取正常产品下的所有分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[1], $executionIdList[0], $mergedBranchesList[0])) && p('1') && e('分支1'); // 获取多分支产品的所有分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[1], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取不存在产品的所有分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[2], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取正常产品下的激活分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[2], $executionIdList[0], $mergedBranchesList[0])) && p('1') && e('分支1'); // 获取多分支产品的激活分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[2], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取不存在产品的激活分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[3], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取正常产品下的非主干分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[3], $executionIdList[0], $mergedBranchesList[0])) && p('1') && e('分支1'); // 获取多分支产品的非主干分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[3], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取不存在产品的非主干分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[4], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取正常产品下的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[4], $executionIdList[0], $mergedBranchesList[0])) && p('1') && e('分支1'); // 获取多分支产品的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[4], $executionIdList[0], $mergedBranchesList[0])) && p()    && e('0');     // 获取不存在产品的包括关闭的分支信息

r($tester->branch->getPairs($productIdList[0], $paramList[0], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[0], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下多分支产品下的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[0], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下不存在产品下的分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[1], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的所有分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[1], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下多分支产品的所有分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[1], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下不存在产品的所有分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[2], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的激活分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[2], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下多分支产品的激活分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[2], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下不存在产品的激活分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[3], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的非主干分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[3], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下多分支产品的非主干分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[3], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下不存在产品的非主干分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[4], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[4], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下多分支产品的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[4], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下不存在产品的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[0], $executionIdList[1], $mergedBranchesList[0])) && p() && e('0'); // 获取执行下正常产品下的分支信息

r($tester->branch->getPairs($productIdList[1], $paramList[0], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下多分支产品下的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[0], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下不存在产品下的分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[1], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下正常产品下的所有分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[1], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下多分支产品的所有分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[1], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下不存在产品的所有分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[2], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下正常产品下的激活分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[2], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下多分支产品的激活分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[2], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下不存在产品的激活分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[3], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下正常产品下的非主干分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[3], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下多分支产品的非主干分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[3], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下不存在产品的非主干分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[4], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下正常产品下的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[4], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下多分支产品的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[4], $executionIdList[2], $mergedBranchesList[0])) && p() && e('0'); // 获取不存在执行下不存在产品的包括关闭的分支信息

r($tester->branch->getPairs($productIdList[0], $paramList[0], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2正常产品下的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[0], $executionIdList[0], $mergedBranchesList[1])) && p('3') && e('分支3');          // 获取不包括分支1、2多分支产品下的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[0], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2不存在产品下的分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[1], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2正常产品下的所有分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[1], $executionIdList[0], $mergedBranchesList[1])) && p('3') && e('分支3');          // 获取不包括分支1、2多分支产品的所有分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[1], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2不存在产品的所有分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[2], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2正常产品下的激活分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[2], $executionIdList[0], $mergedBranchesList[1])) && p('0') && e('主干');           // 获取不包括分支1、2多分支产品的激活分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[2], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2不存在产品的激活分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[3], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2正常产品下的非主干分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[3], $executionIdList[0], $mergedBranchesList[1])) && p('3') && e('分支3');          // 获取不包括分支1、2多分支产品的非主干分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[3], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2不存在产品的非主干分支信息
r($tester->branch->getPairs($productIdList[0], $paramList[4], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2正常产品下的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[1], $paramList[4], $executionIdList[0], $mergedBranchesList[1])) && p('3') && e('分支3 (已关闭)'); // 获取不包括分支1、2多分支产品的包括关闭的分支信息
r($tester->branch->getPairs($productIdList[2], $paramList[4], $executionIdList[0], $mergedBranchesList[1])) && p()    && e('0');              // 获取不包括分支1、2不存在产品的包括关闭的分支信息
