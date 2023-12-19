#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->setURSwitchStatus();
cid=1

- 开源版版本低于18.2，是否能正常打开开关   @1
- 开源版高于18.2的版本默认打开开关,不需要操作数据库   @1
- 企业版低于8.2，是否能正常打开开关   @1
- 企业版高于8.2的版本默认打开开关,不需要操作数据库   @1
- 旗舰版低于4.2，是否能正常打开开关   @1
- 旗舰版高于4.2的版本默认打开开关,不需要操作数据库   @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

$versionList = array('18.1', '18.3', 'biz8.1', 'biz8.3', 'max4.1', 'max4.3');
function getResult()
{
    global $tester;
    unset(dao::$cache['zt_config']);
    return $tester->dao->select('value')
        ->from('zt_config')
        ->where('owner')->eq('system')
        ->andWhere('module')->eq('common')
        ->andWhere('key')->eq('closedFeatures')
        ->fetch('value');
}

function initSwitchStatus()
{
    global $tester;
    $tester->dao->delete()
        ->from('zt_config')
        ->where('owner')->eq('system')
        ->andWhere('module')->eq('common')
        ->andWhere('key')->eq('closedFeatures')
        ->exec();
}

initSwitchStatus();
$upgrade->setURSwitchStatusTest($versionList[0]);
r(strpos(getResult(), 'productUR') !== false) && p('') && e(1);  //开源版本低于18.2的版本是否能正常打开开关

initSwitchStatus();
$result = $upgrade->setURSwitchStatusTest($versionList[1]);
r(strpos(getResult(), 'productUR') === false && $result) && p('') && e(1);  //开源版高于18.2的版本默认打开开关,不需要操作数据库

initSwitchStatus();
$upgrade->setURSwitchStatusTest($versionList[2]);
r(strpos(getResult(), 'productUR') !== false) && p('') && e(1);  //企业版低于8.2的版本是否能正常打开开关

initSwitchStatus();
$result = $upgrade->setURSwitchStatusTest($versionList[3]);
r(strpos(getResult(), 'productUR') === false && $result) && p('') && e(1);  //企业版高于8.2的版本默认打开开关,不需要操作数据库

initSwitchStatus();
$upgrade->setURSwitchStatusTest($versionList[4]);
r(strpos(getResult(), 'productUR') !== false) && p('') && e(1);  //旗舰版低于4.2的版本是否能正常打开开关

initSwitchStatus();
$result = $upgrade->setURSwitchStatusTest($versionList[5]);
r(strpos(getResult(), 'productUR') === false && $result) && p('') && e(1);  //旗舰版高于4.2的版本默认打开开关,不需要操作数据库
