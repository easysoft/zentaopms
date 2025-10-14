#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genWaterpolo();
timeout=0
cid=0

- 执行chartTest模块的genWaterpoloTest方法，参数是'normal' 第series条的0:type属性 @liquidFill
- 执行chartTest模块的genWaterpoloTest方法，参数是'zeroPercent' 第series条的0:data:0属性 @0
- 执行chartTest模块的genWaterpoloTest方法，参数是'highPercent' 第series条的0:data:0属性 @0.95
- 执行chartTest模块的genWaterpoloTest方法，参数是'lowPercent' 第series条的0:data:0属性 @0.05
- 执行chartTest模块的genWaterpoloTest方法，参数是'exactOne' 第series条的0:data:0属性 @1

*/

chdir(dirname(__FILE__, 5));
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

r($chartTest->genWaterpoloTest('normal')) && p('series:0:type') && e('liquidFill');
r($chartTest->genWaterpoloTest('zeroPercent')) && p('series:0:data:0') && e('0');
r($chartTest->genWaterpoloTest('highPercent')) && p('series:0:data:0') && e('0.95');
r($chartTest->genWaterpoloTest('lowPercent')) && p('series:0:data:0') && e('0.05');
r($chartTest->genWaterpoloTest('exactOne')) && p('series:0:data:0') && e('1');