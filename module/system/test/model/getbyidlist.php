#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);
zenData('product')->gen(10);

/**
 *
 * title=测试 systemModel::getByIdList();
 * timeout=0
 * cid=18733
 *
 * - 查询默认排序应用第1条的name属性 @应用1
 * - 获取1,2数据取第1条 @2
 * - 获取3,2数据取第1条 @2
 * - 获取10,11数据取第1条 @10
 * - 获取4,8数据取第1条 @4
 */
global $tester;

$system = $tester->loadModel('system');

r($system->getByIdList([1]))           && p('1:name') && e('应用1');
r(count($system->getByIdList([1, 2]))) && p() && e('2');
r($system->getByIdList([3, 2]))        && p('2:id') && e('2');
r($system->getByIdList([10, 11]))      && p('10:id') && e('10');
r($system->getByIdList([4, 8]))        && p('4:id') && e('4');