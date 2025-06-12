#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->processOldMetrics();
cid=1

- 新数据覆盖检查都通过，说明数据转换成功 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$upgrade = new upgradeTest();

global $tester, $config;

$app->loadLang('metric');
$app->loadModuleConfig('metric');
$scopeMap   = $app->config->metric->oldScopeMap;
$purposeMap = $app->config->metric->oldPurposeMap;
$objectMap  = $app->config->metric->oldObjectMap;

$tester->dao->delete()->from(TABLE_METRIC)->where('fromID')->gt(0)->exec();
$result = $upgrade->processOldMetrics();

$oldMetrics = $tester->dao->select('*')->from(TABLE_BASICMEAS)->where('deleted')->eq('0')->orderBy('order_asc')->fetchAll();

$check = true;
foreach($oldMetrics as $oldMetric)
{
    $metric = $tester->dao->select('*')->from(TABLE_METRIC)->where('fromID')->eq($oldMetric->id)->fetch();
    if(!$metric)
    {
        $check = false;
        break;
    }

    if($metric->scope != zget($scopeMap, $oldMetric->scope, 'other'))
    {
        $check = false;
        break;
    }

    if($metric->purpose != zget($purposeMap, $oldMetric->purpose, 'other'))
    {
        $check = false;
        break;
    }

    if($metric->object != zget($objectMap, $oldMetric->object, 'other'))
    {
        $check = false;
        break;
    }

    if($metric->createdDate != helper::isZeroDate($oldMetric->createdDate) ? null : $oldMetric->createdDate)
    {
        $check = false;
        break;
    }

    if($metric->editedDate != helper::isZeroDate($oldMetric->editedDate) ? null : $oldMetric->editedDate)
    {
        $check = false;
        break;
    }

    $action = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('metric')->andWhere('objectID')->eq($metric->id)->fetch();
    if(!$action)
    {
        $check = false;
        break;
    }
}

r($check) && p('') && e(1); //新数据覆盖检查都通过，说明数据转换成功
