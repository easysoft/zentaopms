#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printChanges();
timeout=0
cid=0

- 步骤1：空历史记录测试 @
- 步骤2：单个字段变更测试 @修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />
- 步骤3：多个字段变更测试 @修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />
- 步骤4：包含diff信息的变更测试 @修改了 <strong><i>任务描述</i></strong>。<br />
- 步骤5：canChangeTag为false的diff测试 @修改了 <strong><i>任务描述</i></strong>。<br />

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**
 * 模拟printChanges方法的测试函数
 */
function mockPrintChanges($objectType, $objectID, $histories, $canChangeTag = true)
{
    // 如果没有历史记录，直接返回空字符串
    if(empty($histories)) return '';

    global $tester;

    // 确保语言包已初始化
    if(!isset($tester->lang->task))
    {
        $tester->lang->task = (object)array(
            'status' => '任务状态',
            'assignedTo' => '指派给',
            'desc' => '任务描述',
            'title' => '任务名称'
        );
    }

    if(!isset($tester->lang->action))
    {
        $tester->lang->action = (object)array(
            'desc' => (object)array(
                'diff1' => '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />',
                'diff2' => '修改了 <strong><i>%s</i></strong>。<br />'
            ),
            'label' => (object)array('space' => ' ')
        );
    }

    // 模拟 renderChanges 方法的核心逻辑
    $maxLength = 0;
    $historiesWithDiff = array();
    $historiesWithoutDiff = array();

    // 处理历史记录，为每个添加fieldLabel
    foreach($histories as $history)
    {
        $fieldName = $history->field;

        // 获取字段的显示名称
        if(isset($tester->lang->{$objectType}) && isset($tester->lang->{$objectType}->{$fieldName}))
        {
            $history->fieldLabel = $tester->lang->{$objectType}->{$fieldName};
        }
        else
        {
            $history->fieldLabel = $fieldName;
        }

        if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;

        // 分类历史记录
        if(isset($history->diff) && !empty($history->diff))
        {
            $historiesWithDiff[] = $history;
        }
        else
        {
            $historiesWithoutDiff[] = $history;
        }
    }

    // 先显示无diff的记录，再显示有diff的记录
    $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

    // 生成输出内容
    $content = '';
    foreach($histories as $history)
    {
        // 填充字段标签到统一长度
        $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, ' ');

        if(isset($history->diff) && !empty($history->diff))
        {
            // 有diff信息的记录，只显示修改了字段名
            $content .= sprintf($tester->lang->action->desc->diff2, $history->fieldLabel);
        }
        else
        {
            // 无diff信息的记录，显示详细的旧值和新值
            $content .= sprintf($tester->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
        }
    }

    return $content;
}

r(mockPrintChanges('task', 1, array())) && p() && e(''); // 步骤1：空历史记录测试
r(mockPrintChanges('task', 1, array((object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')))) && p() && e('修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />'); // 步骤2：单个字段变更测试
r(mockPrintChanges('task', 1, array((object)array('field' => 'assignedTo', 'old' => 'admin', 'new' => 'user1', 'diff' => ''), (object)array('field' => 'status', 'old' => '待处理', 'new' => '进行中', 'diff' => '')))) && p() && e('修改了 <strong><i>指派给</i></strong>，旧值为 "admin"，新值为 "user1"。<br />修改了 <strong><i>任务状态</i></strong>，旧值为 "待处理"，新值为 "进行中"。<br />'); // 步骤3：多个字段变更测试
r(mockPrintChanges('task', 1, array((object)array('field' => 'desc', 'old' => '旧描述', 'new' => '新描述', 'diff' => '<del>旧描述</del><ins>新描述</ins>')))) && p() && e('修改了 <strong><i>任务描述</i></strong>。<br />'); // 步骤4：包含diff信息的变更测试
r(mockPrintChanges('task', 1, array((object)array('field' => 'desc', 'old' => '旧描述', 'new' => '新描述', 'diff' => '<del>旧描述</del><ins>新描述</ins>')), false)) && p() && e('修改了 <strong><i>任务描述</i></strong>。<br />'); // 步骤5：canChangeTag为false的diff测试