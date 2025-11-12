#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::addEditAction();
timeout=0
cid=0

- 测试步骤1:有变更时创建编辑动作属性actionCount @1
- 测试步骤2:有变更和评论时创建编辑动作属性actionCount @1
- 测试步骤3:只有评论无变更时创建评论动作属性actionCount @1
- 测试步骤4:状态从normal变为wait时创建编辑和提交审核两个动作属性actionCount @2
- 测试步骤5:状态从wait变为normal时只创建编辑动作属性actionCount @1
- 测试步骤6:状态保持wait不变时只创建编辑动作属性actionCount @1
- 测试步骤7:有多个变更字段时创建编辑动作属性actionCount @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('case')->loadYaml('case', false, 2)->gen(10);
zendata('action')->gen(0);

su('admin');

$testcaseTest = new testcaseZenTest();

$changes1 = array(array('field' => 'title', 'old' => '旧标题', 'new' => '新标题'));
$changes2 = array();
$changes3 = array(
    array('field' => 'title', 'old' => '旧标题', 'new' => '新标题'),
    array('field' => 'pri', 'old' => '1', 'new' => '2'),
    array('field' => 'type', 'old' => 'feature', 'new' => 'performance')
);
$comment1 = '这是一个评论';

r($testcaseTest->addEditActionTest(1, 'normal', 'normal', $changes1, '')) && p('actionCount') && e('1'); // 测试步骤1:有变更时创建编辑动作
r($testcaseTest->addEditActionTest(2, 'normal', 'normal', $changes1, $comment1)) && p('actionCount') && e('1'); // 测试步骤2:有变更和评论时创建编辑动作
r($testcaseTest->addEditActionTest(3, 'normal', 'normal', $changes2, $comment1)) && p('actionCount') && e('1'); // 测试步骤3:只有评论无变更时创建评论动作
r($testcaseTest->addEditActionTest(4, 'normal', 'wait', $changes1, '')) && p('actionCount') && e('2'); // 测试步骤4:状态从normal变为wait时创建编辑和提交审核两个动作
r($testcaseTest->addEditActionTest(5, 'wait', 'normal', $changes1, '')) && p('actionCount') && e('1'); // 测试步骤5:状态从wait变为normal时只创建编辑动作
r($testcaseTest->addEditActionTest(6, 'wait', 'wait', $changes1, '')) && p('actionCount') && e('1'); // 测试步骤6:状态保持wait不变时只创建编辑动作
r($testcaseTest->addEditActionTest(7, 'normal', 'normal', $changes3, $comment1)) && p('actionCount') && e('1'); // 测试步骤7:有多个变更字段时创建编辑动作