#!/usr/bin/env php
<?php

/**

title=测试 executionZen::initFieldsForCreate();
timeout=0
cid=0

- 步骤1:正常projectID,不提供type
 - 属性project @1
 - 属性type @sprint
- 步骤2:提供type为stage
 - 属性project @1
 - 属性type @stage
- 步骤3:projectID为0
 - 属性project @0
 - 属性type @sprint
- 步骤4:提供type为kanban
 - 属性project @5
 - 属性type @kanban
- 步骤5:提供空type属性project @2
- 步骤6:验证acl默认值属性acl @private
- 步骤7:大projectID值
 - 属性project @999999
 - 属性type @sprint

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

su('admin');

$executionTest = new executionZenTest();

r($executionTest->initFieldsForCreateTest(1, array())) && p('project,type') && e('1,sprint'); // 步骤1:正常projectID,不提供type
r($executionTest->initFieldsForCreateTest(1, array('type' => 'stage'))) && p('project,type') && e('1,stage'); // 步骤2:提供type为stage
r($executionTest->initFieldsForCreateTest(0, array())) && p('project,type') && e('0,sprint'); // 步骤3:projectID为0
r($executionTest->initFieldsForCreateTest(5, array('type' => 'kanban'))) && p('project,type') && e('5,kanban'); // 步骤4:提供type为kanban
r($executionTest->initFieldsForCreateTest(2, array('type' => ''))) && p('project') && e('2'); // 步骤5:提供空type
r($executionTest->initFieldsForCreateTest(3, array())) && p('acl') && e('private'); // 步骤6:验证acl默认值
r($executionTest->initFieldsForCreateTest(999999, array())) && p('project,type') && e('999999,sprint'); // 步骤7:大projectID值