#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getList();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->config('product')->gen(10);
zdTable('build')->config('build')->gen(10);
zdTable('user')->gen(5);
su('admin');

$release = zdTable('release')->config('release');
$release->status->range('normal,terminate');
$release->gen(10);

$products     = array(0, 1, 100);
$branches     = array('all', '0', '1');
$types        = array('all', 'normal', 'terminate', 'bySearch');
$releaseQuery = array("", "( 1   AND t1.`name`  LIKE '%1%' )");

global $tester;
$tester->loadModel('release');
r($tester->release->getList($products[0], $branches[0], $types[0])) && p('0:name') && e('发布5');  // 测试获取系统内所有产品下的发布列表信息
r($tester->release->getList($products[1], $branches[0], $types[0])) && p('0:name') && e('发布3');  // 测试获取产品ID=1下的发布列表信息
r($tester->release->getList($products[2], $branches[0], $types[0])) && p()         && e('0');      // 测试获取产品ID不存在时，发布列表信息
r($tester->release->getList($products[0], $branches[1], $types[0])) && p('0:name') && e('发布5');  // 测试系统内所有产品下的主干分支的发布列表信息
r($tester->release->getList($products[0], $branches[2], $types[0])) && p()         && e('0');      // 测试系统内所有产品下的分支ID=1的发布列表信息
r($tester->release->getList($products[1], $branches[1], $types[0])) && p('0:name') && e('发布3');  // 测试系统内产品ID=1下的主干分支的发布列表信息
r($tester->release->getList($products[1], $branches[2], $types[0])) && p()         && e('0');      // 测试系统内产品ID=1下的分支ID=1的发布列表信息
r($tester->release->getList($products[0], $branches[0], $types[1])) && p('0:name') && e('发布5');  // 测试系统内所有产品下状态是正常的发布列表信息
r($tester->release->getList($products[0], $branches[0], $types[2])) && p('0:name') && e('发布10'); // 测试系统内所有产品下状态是停止维护的发布列表信息
r($tester->release->getList($products[1], $branches[0], $types[1])) && p('0:name') && e('发布3');  // 测试获取产品ID=1下状态是正常的的发布列表信息
r($tester->release->getList($products[1], $branches[0], $types[2])) && p('0:name') && e('发布2');  // 测试获取产品ID=1下状态是停止维护的的发布列表信息
r($tester->release->getList($products[0], $branches[1], $types[1])) && p('0:name') && e('发布5');  // 测试获取系统内所有产品下的主干下状态是正常的的发布列表信息
r($tester->release->getList($products[0], $branches[1], $types[2])) && p('0:name') && e('发布10'); // 测试获取系统内所有产品下的分支ID=1下状态是停止维护的的发布列表信息
r($tester->release->getList($products[1], $branches[1], $types[1])) && p('0:name') && e('发布3');  // 测试获取系统内产品ID=1下的主干下状态是正常的的发布列表信息
r($tester->release->getList($products[1], $branches[1], $types[2])) && p('0:name') && e('发布2');  // 测试获取系统内产品ID=1下的分支ID=1下状态是停止维护的的发布列表信息

r($tester->release->getList($products[0], $branches[0], $types[3], 't1.date_desc', $releaseQuery[1])) && p('0:name') && e('发布10'); // 测试系统内所有产品下根据搜索条件获取的发布列表信息
r($tester->release->getList($products[1], $branches[0], $types[3], 't1.date_desc', $releaseQuery[1])) && p('0:name') && e('发布1');  // 测试系统内产品ID=1下根据搜索条件获取的发布列表信息
r($tester->release->getList($products[1], $branches[1], $types[3], 't1.date_desc', $releaseQuery[1])) && p('0:name') && e('发布1');  // 测试系统内产品ID=1下的分支为主干的根据搜索条件获取的发布列表信息
r($tester->release->getList($products[1], $branches[2], $types[3], 't1.date_desc', $releaseQuery[1])) && p()         && e('0');      // 测试系统内产品ID=1下的分支ID=1根据搜索条件获取的发布列表信息
