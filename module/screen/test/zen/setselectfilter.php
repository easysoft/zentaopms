#!/usr/bin/env php
<?php

/**

title=测试 screenZen::setSelectFilter();
timeout=0
cid=0

- 步骤1：正常输入单个过滤器第source1条的type1属性 @field1
- 步骤2：多个过滤器
 - 第source1条的type1属性 @field1
 - 第source1条的type2属性 @field2
- 步骤3：空filters数组 @0
- 步骤4：不同sourceID第source2条的typeA属性 @fieldA
- 步骤5：相同type覆盖第source1条的type1属性 @field2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$screenTest = new screenZenTest();

r($screenTest->setSelectFilterTest('source1', array(array('type' => 'type1', 'field' => 'field1')))) && p('source1:type1') && e('field1'); // 步骤1：正常输入单个过滤器
r($screenTest->setSelectFilterTest('source1', array(array('type' => 'type1', 'field' => 'field1'), array('type' => 'type2', 'field' => 'field2')))) && p('source1:type1,type2') && e('field1,field2'); // 步骤2：多个过滤器
r($screenTest->setSelectFilterTest('source1', array())) && p() && e('0'); // 步骤3：空filters数组
r($screenTest->setSelectFilterTest('source2', array(array('type' => 'typeA', 'field' => 'fieldA')))) && p('source2:typeA') && e('fieldA'); // 步骤4：不同sourceID
r($screenTest->setSelectFilterTest('source1', array(array('type' => 'type1', 'field' => 'field1'), array('type' => 'type1', 'field' => 'field2')))) && p('source1:type1') && e('field2'); // 步骤5：相同type覆盖