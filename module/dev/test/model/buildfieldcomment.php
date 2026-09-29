#!/usr/bin/env php
<?php

/**

title=测试 devModel::buildFieldComment();
timeout=0
cid=0

- 步骤1：字段标题、描述和关联关系拼接成注释 @所属项目 | 所属项目，对应 zt_project.id | 关联：zt_project.id
- 步骤2：没有描述时只拼接标题和关联关系 @所属项目 | 关联：zt_project.id
- 步骤3：枚举字段追加取值说明 @状态 | 取值：wait=未开始、doing=进行中
- 步骤4：多态关联写出区分字段和映射关系 @附件 | 关联：objectType 指定（bug=zt_bug.id）
- 步骤5：没有字段标题时使用字段名 @project
- 步骤6：描述中的换行被替换为空格 @包含换行 的注释

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

$field     = array('name' => '所属项目', 'description' => '所属项目，对应 zt_project.id', 'optionList' => array());
$reference = array('toTable' => 'zt_project', 'toColumn' => 'id');

$optionField = array('name' => '状态', 'description' => '', 'optionList' => array(array('value' => 'wait', 'label' => '未开始'), array('value' => 'doing', 'label' => '进行中')));
$polyReference = array('type' => 'polymorphic', 'discriminator' => 'objectType', 'targets' => array(array('when' => 'bug', 'toTable' => 'zt_bug', 'toColumn' => 'id')));

r($dev->buildFieldCommentTest('project', $field, $reference)) && p() && e('所属项目 | 所属项目，对应 zt_project.id | 关联：zt_project.id'); // 步骤1：字段标题、描述和关联关系拼接成注释
r($dev->buildFieldCommentTest('project', array('name' => '所属项目', 'description' => '', 'optionList' => array()), $reference)) && p() && e('所属项目 | 关联：zt_project.id'); // 步骤2：没有描述时只拼接标题和关联关系
r($dev->buildFieldCommentTest('status', $optionField)) && p() && e('状态 | 取值：wait=未开始、doing=进行中'); // 步骤3：枚举字段追加取值说明
r($dev->buildFieldCommentTest('objectID', array('name' => '附件', 'description' => '', 'optionList' => array()), $polyReference)) && p() && e('附件 | 关联：objectType 指定（bug=zt_bug.id）'); // 步骤4：多态关联写出区分字段和映射关系
r($dev->buildFieldCommentTest('project', array('name' => '', 'description' => '', 'optionList' => array()))) && p() && e('project'); // 步骤5：没有字段标题时使用字段名
r($dev->buildFieldCommentTest('project', array('name' => '包含换行', 'description' => "包含换行\n的注释", 'optionList' => array()))) && p() && e('包含换行 | 包含换行 的注释'); // 步骤6：描述中的换行被替换为空格
