#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('workflow')->loadYaml('workflow')->gen(10);
zenData('user')->gen(1);
su('admin');

/**

title=测试 myModel->getFlowPairs();
timeout=0
cid=1

- 测试获取流程键值对 @module1:名称1,module2:名称2,module3:名称3,module4:名称4,module6:名称6,module7:名称7,module8:名称8,module9:名称9

*/

$my = new myTest();

r($my->getFlowPairsTest()) && p() && e('module1:名称1,module2:名称2,module3:名称3,module4:名称4,module6:名称6,module7:名称7,module8:名称8,module9:名称9'); // 测试获取流程键值对