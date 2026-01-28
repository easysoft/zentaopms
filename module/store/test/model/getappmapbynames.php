#!/usr/bin/env php
<?php

/**

title=测试 storeModel::getAppMapByNames();
cid=18453

- 测试步骤1：传入空数组参数 >> 期望返回0（无数据）
- 测试步骤2：传入单个应用名称adminer >> 期望返回adminer应用信息
- 测试步骤3：传入多个应用名称 >> 期望返回多个应用信息
- 测试步骤4：使用stable渠道参数 >> 期望返回应用信息
- 测试步骤5：使用dev渠道参数 >> 期望返回0（模拟未找到）

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$store = new storeModelTest();

r($store->getAppMapByNamesTest(array())) && p() && e('0');
r($store->getAppMapByNamesTest(array('adminer'))) && p('adminer:name') && e('adminer');
r($store->getAppMapByNamesTest(array('adminer', 'zentao'))) && p('adminer:name;zentao:name') && e('adminer;zentao');
r($store->getAppMapByNamesTest(array('adminer'), 'stable')) && p('adminer:name') && e('adminer');
r($store->getAppMapByNamesTest(array('nonexistent'), 'dev')) && p() && e('0');