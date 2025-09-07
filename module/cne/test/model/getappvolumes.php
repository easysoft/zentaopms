#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppVolumes();
timeout=0
cid=0

- 步骤1：正常情况获取数据卷第0条的name属性 @data-volume
- 步骤2：获取MySQL组件数据卷第0条的name属性 @mysql-data
- 步骤3：获取Redis组件数据卷第0条的name属性 @redis-data
- 步骤4：非块设备卷第0条的is_block_device属性 @
- 步骤5：不存在的实例 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->getAppVolumesTest(1, false)) && p('0:name') && e('data-volume');
r($cneTest->getAppVolumesTest(2, true)) && p('0:name') && e('mysql-data');
r($cneTest->getAppVolumesTest(3, 'redis')) && p('0:name') && e('redis-data');
r($cneTest->getAppVolumesTest(4, false)) && p('0:is_block_device') && e('');
r($cneTest->getAppVolumesTest(999, false)) && p() && e('');