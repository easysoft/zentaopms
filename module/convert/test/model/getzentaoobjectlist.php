#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoObjectList();
timeout=0
cid=0

- 步骤1:epic存在属性epic @业务需求
- 步骤2:requirement存在属性requirement @用户需求
- 步骤3:requirement仍存在属性requirement @用户需求
- 步骤4:epic仍存在属性epic @业务需求
- 步骤5:story存在属性story @软件需求

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convert = new convertTest();

// 步骤1:当enableER和URAndSR都启用时,检查epic存在
global $config;
$config->enableER = true;
$config->URAndSR = true;
r($convert->getZentaoObjectListTest()) && p('epic') && e('业务需求'); // 步骤1:epic存在

// 步骤2:当enableER和URAndSR都启用时,检查requirement存在
r($convert->getZentaoObjectListTest()) && p('requirement') && e('用户需求'); // 步骤2:requirement存在

// 步骤3:当enableER为false时,检查requirement仍然存在
r($convert->getZentaoObjectListTestWithoutER()) && p('requirement') && e('用户需求'); // 步骤3:requirement仍存在

// 步骤4:当URAndSR为false时,检查epic仍然存在
r($convert->getZentaoObjectListTestWithoutUR()) && p('epic') && e('业务需求'); // 步骤4:epic仍存在

// 步骤5:当启用所有功能时,检查story对象存在
r($convert->getZentaoObjectListTest()) && p('story') && e('软件需求'); // 步骤5:story存在