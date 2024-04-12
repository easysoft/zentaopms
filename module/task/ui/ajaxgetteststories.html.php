<?php
declare(strict_types=1);
/**
 * The ajaxGetTestStories view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
}

$formData   = array();
$storyItems = array();
foreach($testStories as $storyID => $storyTitle)
{
    $storyItems[$storyID] = "#$storyID $storyTitle";
    $formData[] = array('testID' => $storyID, 'testStory' => $storyID);
}

formBatch
(
    setClass('border rounded'),
    set::tagName('div'),
    set::actions(false),
    set::data($formData),
    set::minRows(1),
    formBatchItem
    (
        set::name('testStory'),
        set::label($lang->task->story),
        set::control('picker'),
        set::items($storyItems),
        set::width('auto')
    ),
    formBatchItem
    (
        set::name('testPri'),
        set::label($lang->task->pri),
        set::required(isset($requiredFields['pri'])),
        set::control('priPicker'),
        set::items(array_filter($lang->task->priList)),
        set::width('80px'),
        set::value(empty($task->pri) ? 3 : $task->pri)
    ),
    formBatchItem
    (
        set::name('testEstStarted'),
        set::label($lang->task->estStarted),
        set::required(isset($requiredFields['estStarted'])),
        set::control('date'),
        set::width('140px'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::value(empty($task->estStarted) ? '' : $task->estStarted)
    ),
    formBatchItem
    (
        set::name('testDeadline'),
        set::label($lang->task->deadline),
        set::required(isset($requiredFields['deadline'])),
        set::control('date'),
        set::width('140px'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::value(empty($task->deadline) ? '' : $task->deadline)
    ),
    formBatchItem
    (
        set::name('testAssignedTo'),
        set::label($lang->task->assignedTo),
        set::control('picker'),
        set::items(array()),
        set::width('128px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('testEstimate'),
        set::label($lang->task->estimateAB),
        set::required(isset($requiredFields['estimate'])),
        set::width('80px'),
        set::control(array
        (
            'control'     => 'inputControl',
            'suffix'      => $lang->task->suffixHour,
            'suffixWidth' => 20
        ))
    ),
);
