#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';
su('admin');

/**

title=测试 customModel->getRequiredFields();
timeout=0
cid=1

*/

$emptyConfig = new stdclass();

$taskConfig = new stdclass();
$taskConfig->create = new stdclass();
$taskConfig->create->requiredFields = 'name,begin,end';

$storyConfig = new stdclass();
$storyConfig->edit = new stdclass();
$storyConfig->edit->requiredFields = 'title';

$executionConfig = new stdclass();
$executionConfig->batchedit = new stdclass();
$executionConfig->batchedit->requiredFields = 'name,code,begin,end';

$customTester = new customTest();
r($customTester->getRequiredFieldsTest($emptyConfig))     && p()                 && e('0');                   // 测试空值
r($customTester->getRequiredFieldsTest($taskConfig))      && p('create', ';')    && e('name,begin,end');      // 测试获取任务必填字段
r($customTester->getRequiredFieldsTest($storyConfig))     && p('edit', ';')      && e('title');               // 测试获取需求必填字段
r($customTester->getRequiredFieldsTest($executionConfig)) && p('batchedit', ';') && e('name,code,begin,end'); // 测试获取执行必填字段
