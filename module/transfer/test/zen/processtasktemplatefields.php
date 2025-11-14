#!/usr/bin/env php
<?php

/**

title=测试 transferZen::processTaskTemplateFields();
timeout=0
cid=19341

- 执行transferTest模块的processTaskTemplateFieldsTest方法，参数是1, 'id, name, story, type, status, assignedTo'  @id,name,story,type,status,assignedTo

- 执行transferTest模块的processTaskTemplateFieldsTest方法，参数是2, 'id, name, story, type, status, assignedTo'  @id,name,type,status,assignedTo

- 执行transferTest模块的processTaskTemplateFieldsTest方法，参数是3, 'id, name, story, type, status, assignedTo'  @id,name,type,status,assignedTo

- 执行transferTest模块的processTaskTemplateFieldsTest方法，参数是4, 'id, name, story, type, status, assignedTo'  @id,name,type,status,assignedTo

- 执行transferTest模块的processTaskTemplateFieldsTest方法，参数是5, 'id, name, story, type, status, assignedTo'  @id,name,story,type,status,assignedTo

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->type->range('sprint{1},ops{1},stage{1},waterfall{1},kanban{6}');
$table->attribute->range('devel{1},ops{1},request{1},review{1},design{6}');
$table->gen(10);

su('admin');

$transferTest = new transferZenTest();

r($transferTest->processTaskTemplateFieldsTest(1, 'id,name,story,type,status,assignedTo')) && p() && e('id,name,story,type,status,assignedTo');
r($transferTest->processTaskTemplateFieldsTest(2, 'id,name,story,type,status,assignedTo')) && p() && e('id,name,type,status,assignedTo');
r($transferTest->processTaskTemplateFieldsTest(3, 'id,name,story,type,status,assignedTo')) && p() && e('id,name,type,status,assignedTo');
r($transferTest->processTaskTemplateFieldsTest(4, 'id,name,story,type,status,assignedTo')) && p() && e('id,name,type,status,assignedTo');
r($transferTest->processTaskTemplateFieldsTest(5, 'id,name,story,type,status,assignedTo')) && p() && e('id,name,story,type,status,assignedTo');