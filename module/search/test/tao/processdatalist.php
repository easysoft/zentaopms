#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDataList();
timeout=0
cid=0

- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第1条的comment属性 @创建bug测试附件.txt
- 执行search模块的processDataListTest方法，参数是'case', $caseField, array
 - 第1条的desc属性 @打开系统
 - 第1条的expect属性 @系统正常打开
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第2条的lastEditedDate属性 @2023-01-01 10:00:01
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array @0
- 执行search模块的processDataListTest方法，参数是'bug', $bugField, array 第3条的comment属性 @关闭bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

// 准备测试数据
zenData('bug')->gen(3);
zenData('case')->gen(3);

// 准备action数据 - bug模块
$bugAction = zenData('action');
$bugAction->objectType->range('bug');
$bugAction->objectID->range('1,2,3');
$bugAction->actor->range('admin');
$bugAction->action->range('opened,edited,closed');
$bugAction->date->range('20230101 100000:0,20230102 110000:0,20230103 120000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$bugAction->comment->range('创建bug,修改bug描述,关闭bug');
$bugAction->gen(3);

// 准备action数据 - case模块
$caseAction = zenData('action');
$caseAction->objectType->range('case');
$caseAction->objectID->range('1,2,3');
$caseAction->actor->range('admin');
$caseAction->action->range('opened,changed');
$caseAction->date->range('20230101 090000:0,20230102 100000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$caseAction->comment->range('创建用例,更新用例');
$caseAction->gen(2);

// 准备file数据
$file = zenData('file');
$file->objectType->range('bug,case');
$file->objectID->range('1,2');
$file->title->range('测试附件,用例文档');
$file->extension->range('txt,doc');
$file->gen(2);

// 准备casestep数据
$caseStep = zenData('casestep');
$caseStep->case->range('1,2,3');
$caseStep->version->range('1');
$caseStep->desc->range('打开系统,输入用户名,点击登录');
$caseStep->expect->range('系统正常打开,用户名已输入,登录成功');
$caseStep->gen(3);

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

// 测试步骤2：测试处理case模块数据设置步骤描述和预期结果
r($search->processDataListTest('case', $caseField, array(1))) && p('1:desc,expect') && e('打开系统,系统正常打开');

// 测试步骤3：测试处理数据时日期字段的正确设置（检查lastEditedDate被action的date更新）
r($search->processDataListTest('bug', $bugField, array(2))) && p('2:lastEditedDate') && e('2023-01-01 10:00:01');

// 测试步骤4：测试处理空数据列表时的边界情况
r($search->processDataListTest('bug', $bugField, array())) && p() && e('0');

// 测试步骤5：测试处理不包含相关关联数据的数据项（没有file数据）
r($search->processDataListTest('bug', $bugField, array(3))) && p('3:comment') && e('关闭bug');