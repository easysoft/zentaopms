#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRiskRecord();
timeout=0
cid=18340

- 执行searchTao模块的processRiskRecordTest方法,module为risk,lib为0 >> url属性包含risk-view-1
- 执行searchTao模块的processRiskRecordTest方法,module为risk,lib不为0 >> url属性包含assetlib-riskView-2
- 执行searchTao模块的processRiskRecordTest方法,module为opportunity,lib为0 >> url属性包含opportunity-view-3
- 执行searchTao模块的processRiskRecordTest方法,module为opportunity,lib不为0 >> url属性包含assetlib-opportunityView-4
- 执行searchTao模块的processRiskRecordTest方法,module为risk,objectID为100,lib为0 >> url属性包含risk-view-100
- 执行searchTao模块的processRiskRecordTest方法,module为opportunity,objectID为200,lib为0 >> url属性包含opportunity-view-200
- 执行searchTao模块的processRiskRecordTest方法,module为risk,objectID为50,lib为15 >> url属性包含assetlib-riskView-50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('risk')->gen(10);

su('admin');

global $config;
$config->edition = 'max';

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectType = 'risk';
$record1->objectID = 1;

$record2 = new stdClass();
$record2->objectType = 'risk';
$record2->objectID = 2;

$record3 = new stdClass();
$record3->objectType = 'opportunity';
$record3->objectID = 3;

$record4 = new stdClass();
$record4->objectType = 'opportunity';
$record4->objectID = 4;

$record5 = new stdClass();
$record5->objectType = 'risk';
$record5->objectID = 100;

$record6 = new stdClass();
$record6->objectType = 'opportunity';
$record6->objectID = 200;

$record7 = new stdClass();
$record7->objectType = 'risk';
$record7->objectID = 50;

$risk1 = new stdClass();
$risk1->lib = 0;

$risk2 = new stdClass();
$risk2->lib = 5;

$opportunity1 = new stdClass();
$opportunity1->lib = 0;

$opportunity2 = new stdClass();
$opportunity2->lib = 10;

$risk3 = new stdClass();
$risk3->lib = 0;

$opportunity3 = new stdClass();
$opportunity3->lib = 0;

$risk4 = new stdClass();
$risk4->lib = 15;

$objectList1 = array('risk' => array(1 => $risk1));
$objectList2 = array('risk' => array(2 => $risk2));
$objectList3 = array('opportunity' => array(3 => $opportunity1));
$objectList4 = array('opportunity' => array(4 => $opportunity2));
$objectList5 = array('risk' => array(100 => $risk3));
$objectList6 = array('opportunity' => array(200 => $opportunity3));
$objectList7 = array('risk' => array(50 => $risk4));

r($searchTest->processRiskRecordTest($record1, 'risk', $objectList1)) && p('url') && e('*/risk-view-1.html');
r($searchTest->processRiskRecordTest($record2, 'risk', $objectList2)) && p('url') && e('*/assetlib-riskView-2.html');
r($searchTest->processRiskRecordTest($record3, 'opportunity', $objectList3)) && p('url') && e('*/opportunity-view-3.html');
r($searchTest->processRiskRecordTest($record4, 'opportunity', $objectList4)) && p('url') && e('*/assetlib-opportunityView-4.html');
r($searchTest->processRiskRecordTest($record5, 'risk', $objectList5)) && p('url') && e('*/risk-view-100.html');
r($searchTest->processRiskRecordTest($record6, 'opportunity', $objectList6)) && p('url') && e('*/opportunity-view-200.html');
r($searchTest->processRiskRecordTest($record7, 'risk', $objectList7)) && p('url') && e('*/assetlib-riskView-50.html');
