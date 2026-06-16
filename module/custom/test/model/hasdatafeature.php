#!/usr/bin/env php
<?php

/**

title=测试 customModel::hasDataFeature();
timeout=0
cid=15917

- 步骤1：空数据库时productUR返回false @0
- 步骤2：仅有requirement数据时productUR返回true @1
- 步骤3：仅有epic数据时productUR仍返回true @1
- 步骤4：空数据库时productER返回false @0
- 步骤5：有epic数据时productER返回true @1
- 步骤6：有waterfall项目时waterfall返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(0);
zenData('project')->gen(0);
zenData('user')->gen(5);
su('admin');

$customTester = new customModelTest();

r($customTester->hasDataFeatureTest('productUR')) && p() && e('0'); // 步骤1：空数据库时productUR返回false

$storyTable = zenData('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('0');
$storyTable->gen(3);
r($customTester->hasDataFeatureTest('productUR')) && p() && e('1'); // 步骤2：仅有requirement数据时productUR返回true

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('epic');
$storyTable->deleted->range('0');
$storyTable->gen(3);
r($customTester->hasDataFeatureTest('productUR')) && p() && e('1'); // 步骤3：仅有epic数据时productUR仍返回true

zenData('story')->gen(0);
r($customTester->hasDataFeatureTest('productER')) && p() && e('0'); // 步骤4：空数据库时productER返回false

$storyTable = zenData('story');
$storyTable->type->range('epic');
$storyTable->deleted->range('0');
$storyTable->gen(2);
r($customTester->hasDataFeatureTest('productER')) && p() && e('1'); // 步骤5：有epic数据时productER返回true

zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfall');
$projectTable->deleted->range('0');
$projectTable->gen(2);
r($customTester->hasDataFeatureTest('waterfall')) && p() && e('1'); // 步骤6：有waterfall项目时waterfall返回true