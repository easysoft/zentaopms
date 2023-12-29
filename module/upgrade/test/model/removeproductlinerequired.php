#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->removeProductLineRequired().
cid=1

- 检测产品线必填字段是否已经被移除 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

function initProductLineRequired()
{
    global $tester,$app;
    $appPath = $app->getAppRoot();
    $sqlFile = $appPath . 'test/data/config.sql';
    $tester->dbh->exec(file_get_contents($sqlFile));

    $createRequired = $tester->loadModel('setting')->getItem('owner=system&module=product&section=create&key=requiredFields');
    $editRequired   = $tester->setting->getItem('owner=system&module=product&section=edit&key=requiredFields');

    $createRequiredList = array_filter(explode(',', $createRequired));
    $editRequiredList   = array_filter(explode(',', $editRequired));
    if(!in_array('line', $createRequiredList)) $createRequiredList[] = 'line';
    if(!in_array('line', $editRequiredList))   $editRequiredList[] = 'line';

    $tester->setting->setItem('system.product.create.requiredFields', implode(',', $createRequiredList));
    $tester->setting->setItem('system.product.edit.requiredFields', implode(',', $editRequiredList));
}

function getCheckResult()
{
    global $tester;

    $createRequired = $tester->loadModel('setting')->getItem('owner=system&module=product&section=create&key=requiredFields');
    $editRequired   = $tester->setting->getItem('owner=system&module=product&section=edit&key=requiredFields');

    $createRequiredList = array_filter(explode(',', $createRequired));
    $editRequiredList   = array_filter(explode(',', $editRequired));
    if(in_array('line', $createRequiredList)) return false;
    if(in_array('line', $editRequiredList))   return false;

    return true;
}

initProductLineRequired();

$upgrade->removeProductLineRequired();

r(getCheckResult()) && p('') && e(1);   //检测产品线必填字段是否已经被移除
