#!/usr/bin/env php
<?php

/**

title=测试 jobModel::updateLastTag();
timeout=0
cid=16854

- 执行jobTest模块的updateLastTagTest方法，参数是1, 'testTag' 属性lastTag @testTag
- 执行jobTest模块的updateLastTagTest方法，参数是2, '' 属性lastTag @~~
- 执行jobTest模块的updateLastTagTest方法，参数是3, 'v1.0.0-beta.1' 属性lastTag @v1.0.0-beta.1
- 执行jobTest模块的updateLastTagTest方法，参数是999, 'nonExistTag' 属性lastTag @~~
- 执行jobTest模块的updateLastTagTest方法，参数是5, 'very-long-tag-name-with-multiple-parts-v2.1.0-release-candidate-1' 属性lastTag @very-long-tag-name-with-multiple-parts-v2.1.0-release-candidate-1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

$table = zenData('job');
$table->id->range('1-5');
$table->name->range('job1,job2,job3,job4,job5');
$table->repo->range('1-5');
$table->engine->range('jenkins{3},gitlab{2}');
$table->lastTag->range('');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$jobTest = new jobTest();

r($jobTest->updateLastTagTest(1, 'testTag')) && p('lastTag') && e('testTag');
r($jobTest->updateLastTagTest(2, '')) && p('lastTag') && e('~~');
r($jobTest->updateLastTagTest(3, 'v1.0.0-beta.1')) && p('lastTag') && e('v1.0.0-beta.1');
r($jobTest->updateLastTagTest(999, 'nonExistTag')) && p('lastTag') && e('~~');
r($jobTest->updateLastTagTest(5, 'very-long-tag-name-with-multiple-parts-v2.1.0-release-candidate-1')) && p('lastTag') && e('very-long-tag-name-with-multiple-parts-v2.1.0-release-candidate-1');