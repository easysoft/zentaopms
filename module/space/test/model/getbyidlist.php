#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getByIdList();
timeout=0
cid=0

- 传入空数组获取空间列表并验证结果类型 @1
- 传入有效ID列表获取空间并验证结果类型 @1
- 传入无效ID列表获取空间验证外部API调用返回空数据 @0
- 传入单个ID获取空间并验证结果类型 @1
- showDeleted参数为false获取空间并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->getByIdListTest())) && p() && e('1');                  // 传入空数组获取空间列表并验证结果类型
r(is_array($spaceTester->getByIdListTest(array(1, 2)))) && p() && e('1');       // 传入有效ID列表获取空间并验证结果类型
r(is_array($spaceTester->getByIdListTest(array(9999)))) && p() && e('1');       // 传入无效ID列表获取空间验证外部API调用返回空数据
r(is_array($spaceTester->getByIdListTest(array(1)))) && p() && e('1');          // 传入单个ID获取空间并验证结果类型
r(is_array($spaceTester->getByIdListTest(array(1), false))) && p() && e('1');   // showDeleted参数为false获取空间并验证结果类型
