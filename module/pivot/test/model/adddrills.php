#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addDrills();
timeout=0
cid=0

- 步骤1：非数组设置不处理 @invalid
- 步骤2：缺少 columns 配置时不处理 @0
- 步骤3：存在匹配字段时补充 drill 配置 @bug
- 步骤4：无匹配字段时 drill 使用字段名回退 @severity
- 步骤5：多个字段分别补充对应 drill 配置 @priority

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_PIVOTDRILL)->where('pivot')->eq(1)->exec();
$tester->dao->exec("INSERT INTO " . TABLE_PIVOTDRILL . " (`pivot`, `version`, `field`, `object`, `whereSql`, `condition`, `status`, `account`, `type`) VALUES
    (1, '1', 'status', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (1, '1', 'priority', 'task', 'WHERE pri > 1', '{\"pri\":\">1\"}', 'published', 'admin', 'manual')");

su('admin');

$pivotTest = new pivotModelTest();

$invalidSettingsPivot  = (object)array('id' => 1, 'version' => '1', 'settings' => 'invalid');
$withoutColumnsPivot   = (object)array('id' => 1, 'version' => '1', 'settings' => array('group1' => 'project'));
$singleColumnPivot     = (object)array('id' => 1, 'version' => '1', 'settings' => array('columns' => array(array('field' => 'status'))));
$missingFieldPivot     = (object)array('id' => 1, 'version' => '1', 'settings' => array('columns' => array(array('field' => 'severity'))));
$multipleColumnsPivot  = (object)array('id' => 1, 'version' => '1', 'settings' => array('columns' => array(array('field' => 'status'), array('field' => 'priority'))));

$invalidResult   = $pivotTest->addDrillsTest($invalidSettingsPivot);
$withoutResult   = $pivotTest->addDrillsTest($withoutColumnsPivot);
$singleResult    = $pivotTest->addDrillsTest($singleColumnPivot);
$missingResult   = $pivotTest->addDrillsTest($missingFieldPivot);
$multipleResult  = $pivotTest->addDrillsTest($multipleColumnsPivot);

r($invalidResult->settings) && p() && e('invalid');
r(isset($withoutResult->settings['columns'])) && p() && e('0');
r($singleResult->settings['columns'][0]['drill']->object) && p() && e('bug');
r($missingResult->settings['columns'][0]['drill']) && p() && e('severity');
r($multipleResult->settings['columns'][1]['drill']->field) && p() && e('priority');
