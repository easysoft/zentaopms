#!/usr/bin/env php
<?php

/**

title=测试 bugZen::checkRquiredForEdit();
timeout=0
cid=15443

- 步骤1:正常情况 @1
- 步骤2:title为空属性title @『Bug标题』不能为空。
- 步骤3:openedBuild为空属性openedBuild[] @『影响版本』不能为空。
- 步骤4:resolvedBy有值但resolution为空属性resolution @『解决方案』不能为空。
- 步骤5:resolution为duplicate但duplicateBug为空属性duplicateBug @『重复Bug』不能为空。
- 步骤6:多个必填字段为空
 - 属性title @『Bug标题』不能为空。
 - 属性openedBuild[] @『影响版本』不能为空。
- 步骤7:title只包含空格属性title @『Bug标题』不能为空。
- 步骤8:resolution为非duplicate @1
- 步骤9:所有必填字段为null
 - 属性title @『Bug标题』不能为空。
 - 属性openedBuild[] @『影响版本』不能为空。
- 步骤10:duplicateBug为0属性duplicateBug @『重复Bug』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 设置必填字段配置
global $tester, $app, $config;
$app->rawModule = 'bug';
$app->rawMethod = 'edit';
$config->bug->edit->requiredFields = 'title,openedBuild';

su('admin');

$bugTest = new bugZenTest();

r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p() && e('1'); // 步骤1:正常情况
r($bugTest->checkRquiredForEditTest((object)array('title' => '', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('title') && e('『Bug标题』不能为空。'); // 步骤2:title为空
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('openedBuild[]') && e('『影响版本』不能为空。'); // 步骤3:openedBuild为空
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => 'admin', 'resolution' => '', 'duplicateBug' => ''))) && p('resolution') && e('『解决方案』不能为空。'); // 步骤4:resolvedBy有值但resolution为空
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => ''))) && p('duplicateBug') && e('『重复Bug』不能为空。'); // 步骤5:resolution为duplicate但duplicateBug为空
r($bugTest->checkRquiredForEditTest((object)array('title' => '', 'openedBuild' => '', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('title,openedBuild[]') && e('『Bug标题』不能为空。,『影响版本』不能为空。'); // 步骤6:多个必填字段为空
r($bugTest->checkRquiredForEditTest((object)array('title' => '   ', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => '', 'duplicateBug' => ''))) && p('title') && e('『Bug标题』不能为空。'); // 步骤7:title只包含空格
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'fixed', 'duplicateBug' => ''))) && p() && e('1'); // 步骤8:resolution为非duplicate
r($bugTest->checkRquiredForEditTest((object)array('title' => null, 'openedBuild' => null, 'resolvedBy' => null, 'resolution' => null, 'duplicateBug' => null))) && p('title,openedBuild[]') && e('『Bug标题』不能为空。,『影响版本』不能为空。'); // 步骤9:所有必填字段为null
r($bugTest->checkRquiredForEditTest((object)array('title' => 'Bug Title', 'openedBuild' => 'trunk', 'resolvedBy' => '', 'resolution' => 'duplicate', 'duplicateBug' => '0'))) && p('duplicateBug') && e('『重复Bug』不能为空。'); // 步骤10:duplicateBug为0