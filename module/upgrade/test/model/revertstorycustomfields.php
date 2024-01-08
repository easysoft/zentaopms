#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->revertStoryCustomFields();
cid=1

- 测试删除需求自定义表单项 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$configYaml = zdTable('config');
$configYaml->owner->range('system,admin,dev1,dev2,dev3');
$configYaml->module->range('datatable');
$configYaml->section->range('productBrowse');
$configYaml->key->range('cols');
$configYaml->gen('5');

$upgrade = new upgradeTest();
r($upgrade->revertStoryCustomFieldsTest()) && p() && e('0'); //测试删除需求自定义表单项
