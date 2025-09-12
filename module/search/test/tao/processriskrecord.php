#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRiskRecord();
timeout=0
cid=0

- 步骤1：风险记录无lib字段属性url @/home/z/rzto/module/search/test/tao/processriskrecord.php?m=risk&f=view&id=1
- 步骤2：风险记录有lib字段属性url @/home/z/rzto/module/search/test/tao/processriskrecord.php?m=assetlib&f=riskView&id=6
- 步骤3：机会记录无lib字段属性url @/home/z/rzto/module/search/test/tao/processriskrecord.php?m=opportunity&f=view&id=11
- 步骤4：机会记录有lib字段属性url @/home/z/rzto/module/search/test/tao/processriskrecord.php?m=assetlib&f=opportunityView&id=16
- 步骤5：简单风险记录无lib字段属性url @/home/z/rzto/module/search/test/tao/processriskrecord.php?m=risk&f=view&id=3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$table = zenData('risk');
$table->id->range('1-10');
$table->name->range('Risk1,Risk2,Risk3,Risk4,Risk5');
$table->project->range('1-3');
$table->status->range('active,closed');
$table->lib->range('0{5},1{5}');
$table->gen(10);

$opportunityTable = zenData('opportunity');
$opportunityTable->id->range('11-20');
$opportunityTable->name->range('Opportunity1,Opportunity2,Opportunity3,Opportunity4,Opportunity5');
$opportunityTable->project->range('1-3');
$opportunityTable->status->range('active,closed');
$opportunityTable->lib->range('0{5},1{5}');
$opportunityTable->gen(10);

su('admin');

$searchTest = new searchTest();

// 创建测试记录对象
$riskRecord1 = new stdClass();
$riskRecord1->objectID = 1;
$riskRecord1->objectType = 'risk';
$riskRecord1->title = 'Test Risk 1';

$riskRecord2 = new stdClass();
$riskRecord2->objectID = 6;
$riskRecord2->objectType = 'risk';
$riskRecord2->title = 'Test Risk 2';

$opportunityRecord1 = new stdClass();
$opportunityRecord1->objectID = 11;
$opportunityRecord1->objectType = 'opportunity';
$opportunityRecord1->title = 'Test Opportunity 1';

$opportunityRecord2 = new stdClass();
$opportunityRecord2->objectID = 16;
$opportunityRecord2->objectType = 'opportunity';
$opportunityRecord2->title = 'Test Opportunity 2';

$simpleRecord = new stdClass();
$simpleRecord->objectID = 3;
$simpleRecord->objectType = 'risk';
$simpleRecord->title = 'Simple Risk';

// 创建objectList参数
$objectList = array(
    'risk' => array(
        1 => (object)array('id' => 1, 'lib' => 0),
        3 => (object)array('id' => 3, 'lib' => 0),
        6 => (object)array('id' => 6, 'lib' => 1)
    ),
    'opportunity' => array(
        11 => (object)array('id' => 11, 'lib' => 0),
        16 => (object)array('id' => 16, 'lib' => 1)
    )
);

r($searchTest->processRiskRecordTest($riskRecord1, 'risk', $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processriskrecord.php?m=risk&f=view&id=1'); // 步骤1：风险记录无lib字段
r($searchTest->processRiskRecordTest($riskRecord2, 'risk', $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processriskrecord.php?m=assetlib&f=riskView&id=6'); // 步骤2：风险记录有lib字段
r($searchTest->processRiskRecordTest($opportunityRecord1, 'opportunity', $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processriskrecord.php?m=opportunity&f=view&id=11'); // 步骤3：机会记录无lib字段
r($searchTest->processRiskRecordTest($opportunityRecord2, 'opportunity', $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processriskrecord.php?m=assetlib&f=opportunityView&id=16'); // 步骤4：机会记录有lib字段
r($searchTest->processRiskRecordTest($simpleRecord, 'risk', $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processriskrecord.php?m=risk&f=view&id=3'); // 步骤5：简单风险记录无lib字段