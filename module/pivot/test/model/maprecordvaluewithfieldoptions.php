#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::mapRecordValueWithFieldOptions();
timeout=0
cid=0

- 步骤1：单个 option 字段映射为中文标签 @单元测试环节
- 步骤2：多值 option 字段按逗号映射 @单元测试环节,功能测试环节
- 步骤3：object 字段映射为对象名称 @产品A
- 步骤4：不存在的 option 值保留原值 @unknown
- 步骤5：保留原始字段值 @unittest,feature

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_PRODUCT)->where('id')->in('1,2')->exec();
$tester->dao->insert(TABLE_PRODUCT)->data(array('id' => 1, 'name' => '产品A', 'deleted' => '0'))->exec();
$tester->dao->insert(TABLE_PRODUCT)->data(array('id' => 2, 'name' => '产品B', 'deleted' => '0'))->exec();

su('admin');

$pivotTest = new pivotModelTest();

$records = array(
    (object)array('stage' => 'unittest',         'product' => 1),
    (object)array('stage' => 'unittest,feature', 'product' => 2),
    (object)array('stage' => 'unknown',          'product' => 999)
);

$fields = array(
    'stage'   => array('type' => 'option', 'object' => 'testcase', 'field' => 'stage'),
    'product' => array('type' => 'object', 'object' => 'testcase', 'field' => 'product')
);

$result = $pivotTest->mapRecordValueWithFieldOptionsTest($records, $fields, 'mysql');

r($result[0]->stage) && p() && e('单元测试环节');
r($result[1]->stage) && p() && e('单元测试环节,功能测试环节');
r($result[0]->product) && p() && e('产品A');
r($result[2]->stage) && p() && e('unknown');
r($result[1]->stage_origin) && p() && e('unittest,feature');
