#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDataList();
timeout=0
cid=0

- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第1条的comment属性 @创建bug测试附件.txt
- 执行search模块的processDataListTest方法，参数是'case', $caseField, array 第1条的desc属性 @打开系统
- 执行search模块的processDataListTest方法，参数是'case', $caseField, array 第1条的expect属性 @系统正常打开
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第2条的lastEditedDate属性 @2023-01-01 10:00:01
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

// 简化数据准备，减少复杂度
zenData('bug')->gen(3);
zenData('case')->gen(3);

// 只准备必要的action数据
$action = zenData('action');
$action->objectType->range('bug,case');
$action->objectID->range('1,2,3');
$action->actor->range('admin');
$action->action->range('opened');
$action->date->range('2023-01-01 10:00:00,2023-01-01 10:00:01,2023-01-03 12:00:00');
$action->comment->range('创建bug,修改bug描述,关闭bug');
$action->gen(3);

// 简化file数据
$file = zenData('file');
$file->objectType->range('bug');
$file->objectID->range('1');
$file->title->range('测试附件');
$file->extension->range('txt');
$file->gen(1);

// 简化casestep数据
$caseStep = zenData('casestep');
$caseStep->case->range('1');
$caseStep->version->range('1');
$caseStep->desc->range('打开系统');
$caseStep->expect->range('系统正常打开');
$caseStep->gen(1);

// 定义字段配置
$bugField = new stdclass();
$bugField->id         = 'id';
$bugField->title      = 'title';
$bugField->content    = 'steps,keywords,resolvedBuild';
$bugField->addedDate  = 'openedDate';
$bugField->editedDate = 'lastEditedDate';

$caseField = new stdclass();
$caseField->id         = 'id';
$caseField->title      = 'title';
$caseField->content    = 'precondition,desc,expect';
$caseField->addedDate  = 'openedDate';
$caseField->editedDate = 'lastEditedDate';

$search = new searchTest();

// 测试步骤1：测试处理bug模块数据comment字段合并action和file信息
r($search->processDataListTest('bug', $bugField, array(1))) && p('1:comment') && e('创建bug测试附件.txt');

// 测试步骤2：测试处理case模块数据设置步骤描述和预期结果的desc字段
r($search->processDataListTest('case', $caseField, array(1))) && p('1:desc') && e('打开系统');

// 测试步骤3：测试处理case模块数据设置步骤描述和预期结果的expect字段
r($search->processDataListTest('case', $caseField, array(1))) && p('1:expect') && e('系统正常打开');

// 测试步骤4：测试处理数据时日期字段的正确设置（检查lastEditedDate被action的date更新）
r($search->processDataListTest('bug', $bugField, array(2))) && p('2:lastEditedDate') && e('2023-01-01 10:00:01');

// 测试步骤5：测试处理空数据列表时的边界情况
r($search->processDataListTest('bug', $bugField, array())) && p() && e('0');