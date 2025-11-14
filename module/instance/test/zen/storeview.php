#!/usr/bin/env php
<?php

/**

title=测试 instanceZen::storeView();
timeout=0
cid=16829

- 步骤1:查看不存在的instance实例(ID=999)属性result @fail
- 步骤2:查看ID为0的instance实例属性result @fail
- 步骤3:查看已删除的instance实例(ID=4)属性result @fail
- 步骤4:查看存在但会触发异常的instance实例(ID=1)属性result @fail
- 步骤5:查看另一个不存在的instance实例(ID=100)属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);
zenData('group')->gen(5);

$userGroup = zenData('usergroup');
$userGroup->account->range('admin,user1,user2');
$userGroup->group->range('1');
$userGroup->gen(3);

$groupPriv = zenData('grouppriv');
$groupPriv->group->range('1');
$groupPriv->module->range('space');
$groupPriv->method->range('browse');
$groupPriv->gen(1);

$space = zenData('space');
$space->id->range('1-5');
$space->name->range('Space1,Space2,Space3,Space4,Space5');
$space->k8space->range('space1,space2,space3,space4,space5');
$space->deleted->range('0');
$space->gen(5);

$instance = zenData('instance');
$instance->id->range('1-5');
$instance->name->range('Instance1,Instance2,Instance3,DeletedInstance,Instance5');
$instance->space->range('1-5');
$instance->appName->range('App1,App2,App3,App4,App5');
$instance->appID->range('1-5');
$instance->version->range('1.0,1.1,1.2,1.3,2.0');
$instance->chart->range('zentao,gitlab,sonarqube,jenkins,zentao');
$instance->status->range('stopped,stopped,stopped,stopped,running');
$instance->k8name->range('instance1,instance2,instance3,instance4,instance5');
$instance->domain->range('app1.test.com,app2.test.com,app3.test.com,app4.test.com,app5.test.com');
$instance->deleted->range('0{3},1,0');
$instance->gen(5);

su('admin');

$instanceTest = new instanceZenTest();

r($instanceTest->storeViewTest(999)) && p('result') && e('fail'); // 步骤1:查看不存在的instance实例(ID=999)
r($instanceTest->storeViewTest(0)) && p('result') && e('fail'); // 步骤2:查看ID为0的instance实例
r($instanceTest->storeViewTest(4)) && p('result') && e('fail'); // 步骤3:查看已删除的instance实例(ID=4)
r($instanceTest->storeViewTest(1)) && p('result') && e('fail'); // 步骤4:查看存在但会触发异常的instance实例(ID=1)
r($instanceTest->storeViewTest(100)) && p('result') && e('fail'); // 步骤5:查看另一个不存在的instance实例(ID=100)