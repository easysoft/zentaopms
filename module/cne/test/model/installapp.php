#!/usr/bin/env php
<?php

/**

title=测试 cneModel::installApp();
timeout=0
cid=15625

- 执行cneTest模块的installAppWithFullParamsTest方法 属性code @200
- 执行cneTest模块的installAppWithEmptyChannelTest方法 第data条的channel属性 @stable
- 执行cneTest模块的installAppWithDifferentChartTest方法 第data条的name属性 @gitlab-app
- 执行cneTest模块的installAppWithDifferentNamespaceTest方法 第data条的namespace属性 @production
- 执行cneTest模块的installAppWithNullParamsTest方法 属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->installAppWithFullParamsTest()) && p('code') && e('200');
r($cneTest->installAppWithEmptyChannelTest()) && p('data:channel') && e('stable');
r($cneTest->installAppWithDifferentChartTest()) && p('data:name') && e('gitlab-app');
r($cneTest->installAppWithDifferentNamespaceTest()) && p('data:namespace') && e('production');
r($cneTest->installAppWithNullParamsTest()) && p('code') && e('200');