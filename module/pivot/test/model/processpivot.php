#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processPivot();
timeout=0
cid=0

- 步骤1：对象输入返回对象 @1
- 步骤2：对象输入时解码 settings 为数组 @1
- 步骤3：对象输入时补充 drill 配置 @status
- 步骤4：数组输入返回数组 @2
- 步骤5：数组输入时不补充 drill 配置 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_PIVOTDRILL)->where('pivot')->eq(2)->exec();
$tester->dao->exec("INSERT INTO " . TABLE_PIVOTDRILL . " (`pivot`, `version`, `field`, `object`, `whereSql`, `condition`, `status`, `account`, `type`) VALUES
    (2, '1', 'status', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual')");

su('admin');

$pivotTest = new pivotModelTest();

$objectPivot = (object)array(
    'id'       => 2,
    'version'  => '1',
    'name'     => '{"zh-cn":"透视表A","en":"Pivot A"}',
    'settings' => '{"columns":[{"field":"status"}]}'
);

$arrayPivot1 = (object)array(
    'id'       => 2,
    'version'  => '1',
    'name'     => '{"zh-cn":"透视表B","en":"Pivot B"}',
    'settings' => '{"columns":[{"field":"status"}]}'
);
$arrayPivot2 = (object)array(
    'id'       => 3,
    'version'  => '1',
    'name'     => '{"zh-cn":"透视表C","en":"Pivot C"}',
    'settings' => '{"columns":[{"field":"priority"}]}'
);

$objectResult = $pivotTest->processPivotTest(clone $objectPivot);
$arrayResult  = $pivotTest->processPivotTest(array(clone $arrayPivot1, clone $arrayPivot2), false);

r(is_object($objectResult)) && p() && e('1');
r(is_array($objectResult->settings)) && p() && e('1');
r($objectResult->settings['columns'][0]['drill']->field) && p() && e('status');
r(count($arrayResult)) && p() && e('2');
r(isset($arrayResult[0]->settings['columns'][0]['drill'])) && p() && e('0');
