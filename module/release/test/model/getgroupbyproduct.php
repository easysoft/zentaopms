#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('release')->loadYaml('release')->gen(30);

/**

title=releaseModel->getGroupByProduct();
timeout=0
cid=17989

- 获取系统内所有产品下的发布数据
 - 第0条的product属性 @1
 - 第0条的name属性 @发布1
- 获取系统内产品1、产品2、产品3下的发布数据
 - 第0条的product属性 @1
 - 第0条的name属性 @发布1
- 获取系统内所有产品下的发布数量 @7
- 获取系统内产品1、产品2、产品3下的发布数量 @3

*/

$productIdList = array(array(), array(1, 2, 3));

global $tester;
$tester->loadModel('release');
r(current($tester->release->getGroupByProduct($productIdList[0]))) && p('0:product,name') && e('1,发布1'); // 获取系统内所有产品下的发布数据
r(current($tester->release->getGroupByProduct($productIdList[1]))) && p('0:product,name') && e('1,发布1'); // 获取系统内产品1、产品2、产品3下的发布数据
r(count($tester->release->getGroupByProduct($productIdList[0])))   && p()                 && e('7');       // 获取系统内所有产品下的发布数量
r(count($tester->release->getGroupByProduct($productIdList[1])))   && p()                 && e('3');       // 获取系统内产品1、产品2、产品3下的发布数量