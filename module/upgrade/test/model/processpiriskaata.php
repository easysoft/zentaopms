#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->processPIRiskData();
timeout=0
cid=0

- 获取处理的风险数量 @21
- 获取风险信息
 - 第0条的id属性 @1
 - 第0条的project属性 @0
 - 第0条的execution属性 @0
 - 第0条的PI属性 @1
 - 第0条的name属性 @风险1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('risk')->loadYaml('risk')->gen(30);
zenData('pi')->loadYaml('pi')->gen(10);
zenData('kanbancell')->loadYaml('kanbancell')->gen(10);

$upgradeTest = new upgradeModelTest();
$result      = $upgradeTest->processPIRiskDataTest();
r(count($result)) && p()                                 && e('21');            // 获取处理的风险数量
r($result)        && p('0:id,project,execution,PI,name') && e('1,0,0,1,风险1'); // 获取风险信息
