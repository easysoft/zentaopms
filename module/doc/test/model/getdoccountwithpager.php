#!/usr/bin/env php
<?php

/*

title=测试 docModel::getDocCountWithPager();
timeout=0
cid=16086

- 步骤1：正常获取文档数量 @2
- 步骤2：搜索关键词返回文档数量 @1
- 步骤3：筛选类型为collect @1
- 步骤4：筛选类型为draft @1
- 步骤5：验证方法返回整型 @integer

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTest = new docModelTest();

$table = zenData('doclib');
$table->id->range('1');
$table->type->range('custom');
$table->name->range('测试文档库');
$table->vision->range('rnd');
$table->parent->range('0');
$table->deleted->range('0');
$table->acl->range('open');
$table->gen(1);

$docTable = zenData('doc');
$docTable->lib->range('1');
$docTable->title->range('测试文档1,测试文档2');
$docTable->status->range('normal,draft');
$docTable->vision->range('rnd');
$docTable->addedBy->range('admin');
$docTable->deleted->range('0');
$docTable->gen(2);

$actionTable = zenData('docaction');
$actionTable->doc->range('1');
$actionTable->action->range('collect');
$actionTable->actor->range('admin');
$actionTable->gen(1);

$libs = array(1);
$result = $docTest->getDocCountWithPagerTest($libs);
r($result) && p() && e('2');

$result = $docTest->getDocCountWithPagerTest($libs, '', '测试文档1');
r($result) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'collect');
r($result) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'draft');
r($result) && p() && e('1');

r(gettype($result)) && p() && e('integer');
