#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getObjectLinkParams();
timeout=0
cid=14952

- 执行actionTest模块的getObjectLinkParamsTest方法，参数是$action1, 'libID=%s&apiID=%s&moduleID=%s'  @libID=1&apiID=1&moduleID=0
- 执行actionTest模块的getObjectLinkParamsTest方法，参数是$action2, 'productID=%s'  @productID=1,2

- 执行actionTest模块的getObjectLinkParamsTest方法，参数是$action3, 'type=%s'  @type=cooperation
- 执行actionTest模块的getObjectLinkParamsTest方法，参数是$action4, 'kanbanID=%s'  @kanbanID=1
- 执行actionTest模块的getObjectLinkParamsTest方法，参数是$action5, 'productID=%s'  @productID=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('api')->loadYaml('getobjectlinkparams/api', false, 2)->gen(5);
zenData('kanbanspace')->loadYaml('getobjectlinkparams/kanbanspace', false, 2)->gen(3);
zenData('kanbancard')->loadYaml('getobjectlinkparams/kanbancard', false, 2)->gen(3);
zenData('module')->loadYaml('getobjectlinkparams/module', false, 2)->gen(5);

su('admin');

$actionTest = new actionTaoTest();

$action1 = new stdClass();
$action1->objectType = 'api';
$action1->objectID = 1;

$action2 = new stdClass();
$action2->objectType = 'branch';
$action2->objectID = 1;
$action2->product = '1,2,';

$action3 = new stdClass();
$action3->objectType = 'kanbanspace';
$action3->objectID = 1;

$action4 = new stdClass();
$action4->objectType = 'kanbancard';
$action4->objectID = 1;

$action5 = new stdClass();
$action5->objectType = 'module';
$action5->objectID = 1;
$action5->action = 'undeleted';

r($actionTest->getObjectLinkParamsTest($action1, 'libID=%s&apiID=%s&moduleID=%s')) && p() && e('libID=1&apiID=1&moduleID=0');
r($actionTest->getObjectLinkParamsTest($action2, 'productID=%s')) && p() && e('productID=1,2');
r($actionTest->getObjectLinkParamsTest($action3, 'type=%s')) && p() && e('type=cooperation');
r($actionTest->getObjectLinkParamsTest($action4, 'kanbanID=%s')) && p() && e('kanbanID=1');
r($actionTest->getObjectLinkParamsTest($action5, 'productID=%s')) && p() && e('productID=1');