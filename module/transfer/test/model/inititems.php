#!/usr/bin/env php
<?php

/**

title=测试 transferModel::initItems();
timeout=0
cid=19322

- 步骤1：数据源为空时返回原有选项属性keep @保留项
- 步骤2：选项已有值时不读取系统语言项属性1 @自定义优先级
- 步骤3：任务优先级读取语言项属性1 @1
- 步骤4：任务类型读取语言项属性devel @开发
- 步骤5：选项文本拼接键值属性devel @开发(#devel)
- 步骤6：模块方法获取项目并覆盖系统数据属性11 @项目11
- 步骤7：项目选项拼接键值属性11 @项目11(#11)
- 步骤8：语言项数据源返回关闭原因属性done @已完成
- 步骤9：语言项不存在时返回空 @0
- 步骤10：业务需求优先级使用自身语言项属性1 @需求高
- 步骤11：没有来源方法时使用系统数据属性admin @管理员

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(15);
su('admin');

$transfer    = new transferModelTest();
$emptySource = array('module' => '', 'method' => '', 'params' => '', 'pairs' => '', 'lang' => '');

$keepItems = array('items' => array('keep' => '保留项'), 'dataSource' => array());
r($transfer->initItemsTest('task', 'name', $keepItems)) && p('keep') && e('保留项'); // 步骤1：数据源为空时返回原有选项

$customPri = array('items' => array(1 => '自定义优先级'), 'dataSource' => $emptySource);
r($transfer->initItemsTest('task', 'pri', $customPri)) && p('1') && e('自定义优先级'); // 步骤2：选项已有值时不读取系统语言项

r($transfer->initItemsTest('task', 'pri')) && p('1') && e('1'); // 步骤3：任务优先级读取语言项
r($transfer->initItemsTest('task', 'type')) && p('devel') && e('开发'); // 步骤4：任务类型读取语言项
r($transfer->initItemsTest('task', 'type', array(), true)) && p('devel') && e('开发(#devel)'); // 步骤5：选项文本拼接键值

$transfer->instance->transferConfig->sysDataList['project'] = array(11 => '系统项目');
r($transfer->initItemsTest('task', 'project')) && p('11') && e('项目11'); // 步骤6：模块方法获取项目并覆盖系统数据
r($transfer->initItemsTest('task', 'project', array(), true)) && p('11') && e('项目11(#11)'); // 步骤7：项目选项拼接键值

r($transfer->initItemsTest('story', 'closedReason')) && p('done') && e('已完成'); // 步骤8：语言项数据源返回关闭原因

$missingLang = array('items' => array(1 => '原值'), 'dataSource' => array('module' => '', 'method' => '', 'params' => '', 'pairs' => '', 'lang' => 'notExistsList'));
r($transfer->initItemsTest('story', 'closedReason', $missingLang)) && p() && e('0'); // 步骤9：语言项不存在时返回空

global $app;
$app->loadLang('requirement');
$app->lang->requirement->priList[1] = '需求高';
r($transfer->initItemsTest('requirement', 'pri')) && p('1') && e('需求高'); // 步骤10：业务需求优先级使用自身语言项

$transfer->instance->transferConfig->sysDataList['user'] = array('admin' => '管理员');
$userItems = array('items' => array(), 'dataSource' => $emptySource);
r($transfer->initItemsTest('task', 'user', $userItems)) && p('admin') && e('管理员'); // 步骤11：没有来源方法时使用系统数据
