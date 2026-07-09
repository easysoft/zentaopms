#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getFormAllowedFields();
timeout=0
cid=1

- 步骤1：返回可填写字段数量 @2
- 步骤2：第 1 个字段名 @title
- 步骤3：第 2 个字段名 @assignedTo
- 步骤4：没有页面字段时返回空数组 @0
- 步骤5：workflowaction 加载失败时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$pageFields = array(
    'title'      => (object)array('readonly' => 0),
    'assignedTo' => (object)array('readonly' => 0),
    'pri'        => (object)array('readonly' => 1),
    'sub_story'  => (object)array('readonly' => 0),
);

$allowedFields     = $aiTest->getFormAllowedFieldsTest('story', 'create', $pageFields);
$emptyFields       = $aiTest->getFormAllowedFieldsTest('story', 'create', array());
$loadFailedFields  = $aiTest->getFormAllowedFieldsTest('story', 'create', $pageFields, false);

r(count($allowedFields)) && p() && e('2');
r($allowedFields) && p('0') && e('title');
r($allowedFields) && p('1') && e('assignedTo');
r(count($emptyFields)) && p() && e('0');
r(count($loadFailedFields)) && p() && e('0');
