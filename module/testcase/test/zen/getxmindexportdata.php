#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getXmindExportData();
timeout=0
cid=19099

- 步骤1：正常产品和用例数据导出XMind @1
- 步骤2：空产品ID测试 @1
- 步骤3：普通产品名称测试 @1
- 步骤4：null context数组测试 @error:
- 步骤5：完整context数据测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('product')->loadYaml('product_getxmindexportdata', false, 2)->gen(3);
zendata('module')->loadYaml('module_getxmindexportdata', false, 2)->gen(5);
zendata('case')->loadYaml('case_getxmindexportdata', false, 2)->gen(10);
zendata('casestep')->loadYaml('casestep_getxmindexportdata', false, 2)->gen(30);
zendata('scene')->loadYaml('scene_getxmindexportdata', false, 2)->gen(5);

su('admin');

$testcaseTest = new testcaseZenTest();

$normalContext = array(
    'caseList' => array(),
    'stepList' => array(),
    'sceneMaps' => array(),
    'topScenes' => array(),
    'moduleList' => array(1 => '根模块'),
    'config' => array('root' => '0', 'child' => '1', 'grandchild' => '2')
);

$fullContext = array(
    'caseList' => array(
        (object)array('testcaseID' => 1, 'name' => '测试用例1', 'pri' => 1, 'productName' => '测试产品A', 'moduleID' => 1, 'sceneID' => 1, 'precondition' => ''),
        (object)array('testcaseID' => 2, 'name' => '测试用例2', 'pri' => 1, 'productName' => '测试产品A', 'moduleID' => 2, 'sceneID' => 2, 'precondition' => '')
    ),
    'stepList' => array(
        (object)array('testcaseID' => 1, 'stepID' => 1, 'parentID' => '0', 'desc' => '步骤1', 'expect' => '期望结果1'),
        (object)array('testcaseID' => 1, 'stepID' => 2, 'parentID' => '0', 'desc' => '步骤2', 'expect' => '期望结果2'),
        (object)array('testcaseID' => 2, 'stepID' => 3, 'parentID' => '0', 'desc' => '步骤3', 'expect' => '期望结果3')
    ),
    'sceneMaps' => array(
        1 => (object)array('sceneID' => 1, 'sceneName' => '场景1', 'parentID' => 0, 'moduleID' => 1),
        2 => (object)array('sceneID' => 2, 'sceneName' => '场景2', 'parentID' => 0, 'moduleID' => 2)
    ),
    'topScenes' => array(
        (object)array('sceneID' => 1, 'sceneName' => '场景1', 'parentID' => 0, 'moduleID' => 1),
        (object)array('sceneID' => 2, 'sceneName' => '场景2', 'parentID' => 0, 'moduleID' => 2)
    ),
    'moduleList' => array(1 => '用户管理', 2 => '权限管理'),
    'config' => array('module' => 'module', 'scene' => 'scene', 'case' => 'case', 'precondition' => 'precondition', 'pri' => 'pri', 'group' => 'group')
);

$emptyContext = array(
    'caseList' => null,
    'stepList' => null,
    'sceneMaps' => null,
    'topScenes' => null,
    'moduleList' => null,
    'config' => null
);

r(strlen($testcaseTest->getXmindExportDataTest(1, '测试产品A', $fullContext)) > 1000) && p() && e('1'); // 步骤1：正常产品和用例数据导出XMind
r(strlen($testcaseTest->getXmindExportDataTest(0, '测试产品B', $normalContext)) > 1000) && p() && e('1'); // 步骤2：空产品ID测试
r(strlen($testcaseTest->getXmindExportDataTest(1, '测试产品Normal', $normalContext)) > 1000) && p() && e('1'); // 步骤3：普通产品名称测试
r(substr($testcaseTest->getXmindExportDataTest(1, '测试产品C', $emptyContext), 0, 6)) && p() && e('error:'); // 步骤4：null context数组测试
r(strlen($testcaseTest->getXmindExportDataTest(2, '测试产品B', $fullContext)) > 1000) && p() && e('1'); // 步骤5：完整context数据测试