#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('task')->loadYaml('task', false, 2)->gen(4);
su('admin');

/**

title=测试 searchTao::getEmptySearchCondition();
timeout=0
cid=0

- 步骤1：mysql驱动查询空值 @(`assignedTo` = '' OR `assignedTo` = '0')
- 步骤2：mysql驱动查询非空值 @(`assignedTo` != '' AND `assignedTo` != '0')
- 步骤3：非不等于操作符按空值条件处理 @(`assignedTo` = '' OR `assignedTo` = '0')
- 步骤4：postgres驱动查询空值 @(CAST(`assignedTo` AS TEXT) = '' OR CAST(`assignedTo` AS TEXT) = '0')
- 步骤5：postgres驱动查询非空值 @(CAST(`assignedTo` AS TEXT) != '' AND CAST(`assignedTo` AS TEXT) != '0')
- 步骤6：highgo驱动按pgsql处理 @(CAST(`id` AS TEXT) = '' OR CAST(`id` AS TEXT) = '0')
- 步骤7：非pgsql驱动查询空值 @(`assignedTo` = '' OR `assignedTo` = '0')
- 步骤8：空值条件命中空字符串与0的数据 @2
- 步骤9：非空条件命中非空数据 @2

*/

$search = new searchTaoTest();

r($search->getEmptySearchConditionTest('assignedTo', '=')) && p() && e("(`assignedTo` = '' OR `assignedTo` = '0')"); // 步骤1：mysql驱动查询空值
r($search->getEmptySearchConditionTest('assignedTo', '!=')) && p() && e("(`assignedTo` != '' AND `assignedTo` != '0')"); // 步骤2：mysql驱动查询非空值
r($search->getEmptySearchConditionTest('assignedTo', '<')) && p() && e("(`assignedTo` = '' OR `assignedTo` = '0')"); // 步骤3：非不等于操作符按空值条件处理
r($search->getEmptySearchConditionTest('assignedTo', '=', 'postgres')) && p() && e("(CAST(`assignedTo` AS TEXT) = '' OR CAST(`assignedTo` AS TEXT) = '0')"); // 步骤4：postgres驱动查询空值
r($search->getEmptySearchConditionTest('assignedTo', '!=', 'postgres')) && p() && e("(CAST(`assignedTo` AS TEXT) != '' AND CAST(`assignedTo` AS TEXT) != '0')"); // 步骤5：postgres驱动查询非空值
r($search->getEmptySearchConditionTest('id', '=', 'highgo')) && p() && e("(CAST(`id` AS TEXT) = '' OR CAST(`id` AS TEXT) = '0')"); // 步骤6：highgo驱动按pgsql处理
r($search->getEmptySearchConditionTest('assignedTo', '=', 'dm')) && p() && e("(`assignedTo` = '' OR `assignedTo` = '0')"); // 步骤7：非pgsql驱动查询空值
r($search->getEmptySearchConditionDataTest('=')) && p() && e('2'); // 步骤8：空值条件命中空字符串与0的数据
r($search->getEmptySearchConditionDataTest('!=')) && p() && e('2'); // 步骤9：非空条件命中非空数据
